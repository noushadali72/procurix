<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\VendorBill;
use App\Services\UnitConversionService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendorBillController extends Controller
{
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

        $purchaseOrders = PurchaseOrder::with('vendor')
            ->where('status', 'received')
            ->whereDoesntHave('vendorBill')
            ->latest()
            ->get();

        return view(
            'vendor_bills.index',
            compact('vendorBills', 'purchaseOrders')
        );
    }


    /**
     * Generate vendor bill from selected purchase order.
     */
    public function store(
        Request $request,
        UnitConversionService $conversion
    ) {
        $request->validate([
            'purchase_order_id' => [
                'required',
                'exists:purchase_orders,id',
            ],
        ]);

        $purchaseOrder = PurchaseOrder::findOrFail(
            $request->purchase_order_id
        );

        return $this->createVendorBill(
            $purchaseOrder,
            $conversion
        );
    }


    /**
     * Display vendor bill.
     */
    // public function show(VendorBill $vendorBill)
    // {
    //     $vendorBill->load([
    //         'vendor',
    //         'items',
    //         'items.rawMaterial',
    //         'items.unit',
    //         'purchaseOrder',
    //         'vendorPayments',
    //         'creditApplications',
    //     ]);

    //     return view(
    //         'vendor_bills.show',
    //         compact('vendorBill')
    //     );
    // }

    public function show(VendorBill $vendorBill)
    {
        $vendorBill->load([
            'vendor',
            'items',
            'items.rawMaterial',
            'items.unit',
            'purchaseOrder',
            'vendorPayments',
            'creditApplications',
        ]);

        return view(
            'vendor_bills.show',
            compact('vendorBill')
        );
    }


    /**
     * Generate vendor bill directly from purchase order.
     */
    public function generate(
        PurchaseOrder $purchaseOrder,
        UnitConversionService $conversion
    ) {
        return $this->createVendorBill(
            $purchaseOrder,
            $conversion
        );
    }


    /**
     * Shared vendor bill generation logic.
     *
     * This method calculates the bill from:
     *
     * received quantity
     * -
     * completed returned quantity
     *
     * Therefore returns made BEFORE bill generation
     * automatically reduce the vendor bill.
     */
    private function createVendorBill(
        PurchaseOrder $purchaseOrder,
        UnitConversionService $conversion
    ) {
        if ($purchaseOrder->status !== 'received') {
            return response()->json([
                'message' =>
                'Vendor bill can only be generated for a received purchase order.'
            ], 422);
        }

        if ($purchaseOrder->vendorBill()->exists()) {
            return response()->json([
                'message' =>
                'Vendor bill already exists for this purchase order.'
            ], 422);
        }

        /*
         * Load everything required to calculate:
         *
         * received quantity
         * returned quantity
         * billable quantity
         */
        $purchaseOrder->load([
            'items.unit',

            'items.goodsReceiptItems.unit',

            'items.goodsReceiptItems.purchaseReturnItems.unit',

            'items.goodsReceiptItems.purchaseReturnItems.purchaseReturn',
        ]);

        try {

            $vendorBill = DB::transaction(function () use (
                $purchaseOrder,
                $conversion
            ) {

                /*
                 * Recheck inside transaction so two requests cannot
                 * intentionally generate the same bill through normal flow.
                 */
                if (VendorBill::where('purchase_order_id',$purchaseOrder->id)->exists()
                ) {
                    throw new \RuntimeException(
                        'Vendor bill already exists for this purchase order.'
                    );
                }

                $term = $purchaseOrder->vendor->paymentTerm;
                $due_days = $term->due_days;
                
                $vendorBill = VendorBill::create([
                    'purchase_order_id' => $purchaseOrder->id,
                    'vendor_id' => $purchaseOrder->vendor_id,
                    'bill_date' => now()->toDateString(),
                    'due_date' => now()->addDays($due_days??3)->toDateString(),
                    'subtotal' => 0,
                    'tax' => 0,
                    'total' => 0,
                    'status' => 'unpaid',
                    'notes' => '',
                ]);

                $subtotal = 0;

                foreach ($purchaseOrder->items as $orderItem) {

                    /*
                     * -------------------------------------------
                     * 1. Calculate total received quantity
                     * -------------------------------------------
                     *
                     * Goods Receipts may use different compatible
                     * units, therefore convert everything into
                     * the Purchase Order item's unit.
                     */
                    $receivedQty = $orderItem
                        ->goodsReceiptItems
                        ->sum(function ($receiptItem) use (
                            $conversion,
                            $orderItem
                        ) {
                            return $conversion->convert(
                                (float) $receiptItem->qty,
                                $receiptItem->unit,
                                $orderItem->unit
                            );
                        });


                    /*
                     * -------------------------------------------
                     * 2. Calculate completed returned quantity
                     * -------------------------------------------
                     *
                     * A return belongs to a Goods Receipt Item.
                     * Only completed returns affect the bill.
                     */
                    $returnedQty = $orderItem
                        ->goodsReceiptItems
                        ->sum(function ($receiptItem) use (
                            $conversion,
                            $orderItem
                        ) {

                            return $receiptItem
                                ->purchaseReturnItems
                                ->sum(function ($returnItem) use (
                                    $conversion,
                                    $orderItem
                                ) {

                                    if (
                                        $returnItem
                                        ->purchaseReturn
                                        ?->status !== 'completed'
                                    ) {
                                        return 0;
                                    }

                                    return $conversion->convert(
                                        (float) $returnItem->qty,
                                        $returnItem->unit,
                                        $orderItem->unit
                                    );
                                });
                        });


                    /*
                     * -------------------------------------------
                     * 3. Net billable quantity
                     * -------------------------------------------
                     */
                    $billableQty = max(
                        0,
                        $receivedQty - $returnedQty
                    );


                    /*
                     * Don't create empty bill lines.
                     *
                     * Example:
                     * received 10
                     * returned 10
                     * billable 0
                     */
                    if ($billableQty <= 0) {
                        continue;
                    }


                    /*
                     * -------------------------------------------
                     * 4. Calculate financial value
                     * -------------------------------------------
                     */
                    $unitCost = (float) $orderItem->unit_cost;

                    $lineTotal = round(
                        $billableQty * $unitCost,
                        2
                    );

                    $subtotal += $lineTotal;


                    /*
                     * -------------------------------------------
                     * 5. Create Vendor Bill Item
                     * -------------------------------------------
                     */
                    $vendorBill->items()->create([
                        'raw_material_id' =>
                        $orderItem->raw_material_id,

                        'qty' =>
                        $billableQty,

                        'unit_id' =>
                        $orderItem->unit_id,

                        'unit_cost' =>
                        $unitCost,

                        'line_total' =>
                        $lineTotal,
                    ]);
                }


                /*
                 * Don't create a zero-value Vendor Bill.
                 *
                 * Example:
                 * received everything
                 * returned everything
                 * before bill generation
                 */
                if ($vendorBill->items()->doesntExist()) {

                    /*
                     * Because we're inside a transaction,
                     * throwing this exception rolls back
                     * the VendorBill that was just created.
                     */
                    throw new \RuntimeException(
                        'There is no billable quantity remaining for this purchase order.'
                    );
                }


                $vendorBill->update([
                    'subtotal' => round($subtotal, 2),
                    'total' => round($subtotal, 2),
                ]);

                return $vendorBill;
            });


            return response()->json([
                'message' =>
                'Vendor bill generated successfully.',

                'redirect' => route(
                    'vendor-bills.show',
                    $vendorBill
                ),
            ]);
        } catch (\RuntimeException $e) {

            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        } catch (\Throwable $e) {

            report($e);

            return response()->json([
                'message' =>
                'Unable to generate vendor bill.',
            ], 500);
        }
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
