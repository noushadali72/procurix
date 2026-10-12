<?php

namespace App\Http\Controllers;

use App\Http\Requests\VendorBill\StoreVendorBillRequest;
use App\Models\Account;
use App\Models\PurchaseOrder;
use App\Models\RawMaterial;
use App\Models\Unit;
use App\Models\Vendor;
use App\Models\VendorBill;
use App\Services\JournalEntryService;
use App\Services\UnitConversionService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class VendorBillController extends Controller
{
    public function __construct(private JournalEntryService $journalEntryService) {}

    /**
     * Display vendor bills and purchase orders
     * available for bill generation.
     */
    public function index()
    {
        $vendorBills = VendorBill::with([
            'vendor',
            'purchaseOrder',
        ])
            ->latest()
            ->paginate(10);


        $purchaseOrders = PurchaseOrder::with([
            'vendor',
            'goodsReceipts.items.purchaseReturnItems',
        ])
            ->where('status', 'received')
            ->whereDoesntHave('vendorBill')
            ->latest()
            ->get()
            ->filter(fn($purchaseOrder) => $purchaseOrder->returnable_qty > 0)
            ->values();

        return view(
            'vendor_bills.index',
            compact('vendorBills', 'purchaseOrders')
        );
    }


    public function create()
    {

        $vendors = Vendor::all();
        $purchaseOrders = PurchaseOrder::with(['vendor', 'items', 'items.rawMaterial', 'items.unit'])->where('status', 'received')->whereDoesntHave('vendorBill')->get()->filter(fn($purchaseOrder) => $purchaseOrder->returnable_qty > 0)->values();
        $rawMaterials = RawMaterial::all();
        $units = Unit::all();



        return view('vendor_bills.create', compact('vendors', 'purchaseOrders', 'rawMaterials', 'units'));
    }


    /**
     * Generate vendor bill from selected purchase order.
     */
    public function store(
        StoreVendorBillRequest $request,
        UnitConversionService $conversion
    ) {
        if ($request->filled('purchase_order_id')) {
            return $this->storeWithPO($request, $conversion);
        }

        return $this->storeWithoutPO($request);
    }

    private function storeWithPO(
        StoreVendorBillRequest $request,
        UnitConversionService $conversion
    ) {
        $data = $request->validated();

        $purchaseOrder = PurchaseOrder::with([
            'vendor.paymentTerm',
            'purchaseRequest',
            'items.unit',
            'items.goodsReceiptItems.unit',
            'items.goodsReceiptItems.purchaseReturnItems.unit',
            'items.goodsReceiptItems.purchaseReturnItems.purchaseReturn',
        ])->findOrFail($data['purchase_order_id']);

        if ($purchaseOrder->status !== 'received') {
            return response()->json([
                'message' => 'A vendor bill can only be created for a received purchase order.',
            ], 422);
        }

        if ($purchaseOrder->vendorBill()->exists()) {
            return response()->json([
                'message' => 'A vendor bill already exists for this purchase order.',
            ], 422);
        }

        if ((int) $data['vendor_id'] !== (int) $purchaseOrder->vendor_id) {
            return response()->json([
                'message' => 'The selected vendor does not match the purchase order.',
            ], 422);
        }

        $availableQuantities = $this->getBillableQuantities(
            $purchaseOrder,
            $conversion
        );

        $requestedQuantities = [];

        foreach ($data['items'] as $item) {
            $key = $item['raw_material_id'] . ':' . $item['unit_id'];

            if (!isset($availableQuantities[$key])) {
                return response()->json([
                    'message' => 'A bill item does not belong to the selected purchase order.',
                ], 422);
            }

            $requestedQuantities[$key] = ($requestedQuantities[$key] ?? 0)
                + (float) $item['qty'];

            if ($requestedQuantities[$key] > $availableQuantities[$key] + 0.000001) {
                return response()->json([
                    'message' => 'Bill quantity cannot exceed the net quantity received.',
                ], 422);
            }
        }

        return $this->createBill($data, $purchaseOrder, '2200');
    }

    private function storeWithoutPO(StoreVendorBillRequest $request)
    {
        return $this->createBill(
            $request->validated(),
            null,
            null
        );
    }

    private function createBill(
        array $data,
        ?PurchaseOrder $purchaseOrder,
        ?string $debitAccountCode
    ) {
        try {
            $vendorBill = DB::transaction(function () use (
                $data,
                $purchaseOrder,
                $debitAccountCode
            ) {
                if (
                    $purchaseOrder &&
                    VendorBill::where('purchase_order_id', $purchaseOrder->id)->exists()
                ) {
                    throw new \RuntimeException(
                        'A vendor bill already exists for this purchase order.'
                    );
                }

                $vendor = Vendor::with('paymentTerm')->findOrFail(
                    $data['vendor_id']
                );

                $billDate = $data['bill_date'] ?? now()->toDateString();
                $dueDays = $vendor->paymentTerm?->due_days ?? 3;

                $dueDate = $data['due_date']
                    ?? \Carbon\Carbon::parse($billDate)
                    ->addDays($dueDays)
                    ->toDateString();

                $subtotal = collect($data['items'])->sum(
                    fn($item) => (float) $item['qty'] * (float) $item['unit_cost']
                );

                $subtotal = round($subtotal, 2);
                $tax = round((float) ($data['tax'] ?? 0), 2);
                $total = round($subtotal + $tax, 2);

                $vendorBill = VendorBill::create([
                    'purchase_order_id' => $purchaseOrder?->id,
                    'vendor_id' => $vendor->id,
                    'bill_date' => $billDate,
                    'due_date' => $dueDate,
                    'subtotal' => $subtotal,
                    'tax' => $tax,
                    'total' => $total,
                    'status' => 'unpaid',
                    'notes' => $data['notes'] ?? '',
                ]);

                $items = collect($data['items'])->map(function ($item) {
                    return [
                        'raw_material_id' => $item['raw_material_id'],
                        'qty' => $item['qty'],
                        'unit_id' => $item['unit_id'],
                        'unit_cost' => $item['unit_cost'],
                        'line_total' => round(
                            (float) $item['qty'] * (float) $item['unit_cost'],
                            2
                        ),
                    ];
                })->all();

                $vendorBill->items()->createMany($items);

                if ($debitAccountCode) {

                    $debitAccount = Account::where('code', $debitAccountCode)->firstOrFail();
                    $payableAccount = Account::where('code', '2100')->firstOrFail();

                    $this->journalEntryService->post(
                        [
                            'entry_date' => $billDate,
                            'description' => "Vendor bill {$vendorBill->bill_number}.",
                            'reference' => $vendorBill,
                        ],
                        [
                            [
                                'account_id' => $debitAccount->id,
                                'debit' => $total,
                                'credit' => 0,
                                'description' => "Vendor bill {$vendorBill->bill_number}.",
                            ],
                            [
                                'account_id' => $payableAccount->id,
                                'debit' => 0,
                                'credit' => $total,
                                'description' => "Accounts payable for {$vendorBill->bill_number}.",
                            ],
                        ]
                    );
                } else {
                    /*
                        // Enable this when direct bills should debit Inventory.

                        $inventoryAccount = Account::where('code', '1100')->firstOrFail();
                        $payableAccount = Account::where('code', '2100')->firstOrFail();

                        $this->journalEntryService->post(
                            [
                                'entry_date' => $billDate,
                                'description' => "Vendor bill {$vendorBill->bill_number}.",
                                'reference' => $vendorBill,
                            ],
                            [
                                [
                                    'account_id' => $inventoryAccount->id,
                                    'debit' => $total,
                                    'credit' => 0,
                                    'description' => "Inventory for {$vendorBill->bill_number}.",
                                ],
                                [
                                    'account_id' => $payableAccount->id,
                                    'debit' => 0,
                                    'credit' => $total,
                                    'description' => "Accounts payable for {$vendorBill->bill_number}.",
                                ],
                            ]
                        );
                        */
                }

                if ($purchaseOrder?->purchaseRequest) {
                    recordActivity(
                        'Vendor Bill created.',
                        $vendorBill,
                        "Vendor Bill {$vendorBill->bill_number} was created for Purchase Order {$purchaseOrder->order_number}.",
                        $purchaseOrder
                    );
                } else {

                    recordActivity(
                        "Vendor Bill created.",
                        $vendorBill,
                        "Vendor Bill {$vendorBill->bill_number} was created.",
                    );
                }

                return $vendorBill;
            });

            return response()->json([
                'success' => true,
                'message' => 'Vendor bill created successfully.',
                'redirect' => route('vendor-bills.show', $vendorBill),
            ]);
        } catch (\RuntimeException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Unable to create vendor bill.',
            ], 500);
        }
    }

    private function getBillableQuantities(
        PurchaseOrder $purchaseOrder,
        UnitConversionService $conversion
    ): array {
        $quantities = [];

        foreach ($purchaseOrder->items as $orderItem) {
            $receivedQty = $orderItem->goodsReceiptItems->sum(
                fn($receiptItem) => $conversion->convert(
                    (float) $receiptItem->qty,
                    $receiptItem->unit,
                    $orderItem->unit
                )
            );

            $returnedQty = $orderItem->goodsReceiptItems->sum(
                function ($receiptItem) use ($conversion, $orderItem) {
                    return $receiptItem->purchaseReturnItems->sum(
                        function ($returnItem) use ($conversion, $orderItem) {
                            if ($returnItem->purchaseReturn?->status !== 'completed') {
                                return 0;
                            }

                            return $conversion->convert(
                                (float) $returnItem->qty,
                                $returnItem->unit,
                                $orderItem->unit
                            );
                        }
                    );
                }
            );

            $key = $orderItem->raw_material_id . ':' . $orderItem->unit_id;

            $quantities[$key] = ($quantities[$key] ?? 0)
                + max(0, $receivedQty - $returnedQty);
        }

        return $quantities;
    }


    public function show(VendorBill $vendorBill)
    {
        $vendorBill->load([
            'vendor',
            'items',
            'items.rawMaterial',
            'items.unit',
            'purchaseOrder',
            'purchaseOrder.purchaseRequest.activities.user',
            'purchaseOrder.purchaseRequest.activities.vendor',
            'vendorPayments',
            'creditApplications',
        ]);

        return view(
            'vendor_bills.show',
            compact('vendorBill')
        );
    }


    /**
     * Generate Vendor Bill PDF.
     */
    public function generatePdf(VendorBill $vendorBill)
    {
        $vendorBill->load([
            'vendor',
            'purchaseOrder',
            'items',
            'items.rawMaterial',
            'items.unit',
        ]);

        $pdf = Pdf::loadView(
            'vendor_bills.pdf',
            [
                'vendorBill' => $vendorBill
            ]
        );

        return $pdf->download(
            $vendorBill->bill_number . '.pdf'
        );
    }
}
