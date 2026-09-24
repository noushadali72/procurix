<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseReturn\StorePurchaseReturnRequest;
use App\Models\GoodsReceipt;
use App\Models\GoodsReceiptItem;
use App\Models\PurchaseReturn;
use App\Models\RawMaterial;
use App\Models\VendorBill;
use App\Models\VendorCredit;
use App\Services\UnitConversionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class PurchaseReturnController extends Controller
{
    public function index()
    {
        $purchaseReturns = PurchaseReturn::with([
            'goodsReceipt.purchaseOrder.vendor',
            'items',
            'vendorCredit',
        ])
            ->latest()
            ->paginate(15);

        return view(
            'purchase_returns.index',
            compact('purchaseReturns')
        );
    }


    public function create(GoodsReceipt $goodsReceipt)
    {

        $goodsReceipt->load([
            'purchaseOrder.vendor',
            'items.unit',
            'items.purchaseOrderItem.rawMaterial.unit',
            'items.purchaseOrderItem.unit',
            'items.purchaseReturnItems.unit',
            'items.purchaseReturnItems.purchaseReturn',
        ]);

        return view(
            'purchase_returns.create',
            compact('goodsReceipt')
        );
    }


    public function store(
        StorePurchaseReturnRequest $request,
        GoodsReceipt $goodsReceipt,
        UnitConversionService $conversion
    ): JsonResponse {
        $validated = $request->validated();

        try {

            $purchaseReturn = DB::transaction(function () use (
                $validated,
                $goodsReceipt,
                $conversion
            ) {

                /*
                 * Lock the Goods Receipt because we're calculating
                 * returnable quantities against it.
                 */
                $lockedReceipt = GoodsReceipt::query()
                    ->lockForUpdate()
                    ->findOrFail($goodsReceipt->id);

                $lockedReceipt->load([
                    'purchaseOrder.vendor',
                ]);

                /*
                 * Ignore rows where return quantity is zero.
                 */
                $returnItems = collect($validated['items'])
                    ->filter(function ($item) {
                        return (float) ($item['qty'] ?? 0) > 0;
                    })
                    ->values();

                if ($returnItems->isEmpty()) {
                    throw new \RuntimeException(
                        'Enter a return quantity for at least one material.'
                    );
                }

                $purchaseReturn = PurchaseReturn::create([
                    'goods_receipt_id' => $lockedReceipt->id,
                    'return_number' => $this->generateReturnNumber(),
                    'return_date' => $validated['return_date'],
                    'status' => 'draft',
                    'reason' => $validated['reason'] ?? null,
                    'notes' => $validated['notes'] ?? null,
                ]);

                $returnTotal = 0;

                foreach ($returnItems as $item) {

                    /*
                     * Important:
                     * The receipt item MUST belong to this Goods Receipt.
                     */
                    $receiptItem = GoodsReceiptItem::query()
                        ->where('goods_receipt_id', $lockedReceipt->id)
                        ->with([
                            'unit',
                            'purchaseOrderItem.unit',
                            'purchaseOrderItem.rawMaterial.unit',
                            'purchaseReturnItems.unit',
                            'purchaseReturnItems.purchaseReturn',
                        ])
                        ->lockForUpdate()
                        ->findOrFail(
                            $item['goods_receipt_item_id']
                        );

                    $orderItem = $receiptItem->purchaseOrderItem;
                    $rawMaterial = $orderItem->rawMaterial;

                    if (!$rawMaterial) {
                        throw new \RuntimeException(
                            'Raw material for one of the receipt items no longer exists.'
                        );
                    }

                    /*
                     * Existing returns may theoretically use another
                     * compatible unit, so convert them into the original
                     * Goods Receipt item's unit before summing.
                     */
                    $alreadyReturned = $receiptItem
                        ->purchaseReturnItems
                        ->sum(function ($returnItem) use (
                            $conversion,
                            $receiptItem
                        ) {
                            /*
                             * Only completed returns affect returnable qty.
                             */
                            if (
                                $returnItem->purchaseReturn?->status
                                !== 'completed'
                            ) {
                                return 0;
                            }

                            return $conversion->convert(
                                (float) $returnItem->qty,
                                $returnItem->unit,
                                $receiptItem->unit
                            );
                        });

                    $returnableQty = max(
                        0,
                        (float) $receiptItem->qty - $alreadyReturned
                    );

                    $returnQty = (float) $item['qty'];

                    if ($returnQty > $returnableQty) {
                        throw new \RuntimeException(
                            "Return quantity for {$rawMaterial->name} cannot exceed {$returnableQty} {$receiptItem->unit->short_name}."
                        );
                    }

                    /*
                     * Convert returned quantity into PO item's unit
                     * to calculate the financial value.
                     */
                    $qtyInOrderUnit = $conversion->convert(
                        $returnQty,
                        $receiptItem->unit,
                        $orderItem->unit
                    );

                    $unitCost = (float) $orderItem->unit_cost;

                    $lineTotal = round(
                        $qtyInOrderUnit * $unitCost,
                        2
                    );

                    $purchaseReturn->items()->create([
                        'goods_receipt_item_id' => $receiptItem->id,
                        'qty' => $returnQty,
                        'unit_id' => $receiptItem->unit_id,
                        'unit_cost' => $unitCost,
                        'line_total' => $lineTotal,
                        'reason' => null,
                    ]);

                    /*
                     * Convert return quantity into the Raw Material's
                     * stock unit.
                     */
                    $stockQty = $conversion->convert(
                        $returnQty,
                        $receiptItem->unit,
                        $rawMaterial->unit
                    );

                    $lockedRawMaterial = RawMaterial::query()
                        ->lockForUpdate()
                        ->findOrFail($rawMaterial->id);

                    /*
                     * For this simple inventory system we don't allow
                     * a return to create negative stock.
                     *
                     * Example:
                     * Received 100
                     * Used in manufacturing 95
                     * Current stock 5
                     * Trying to return 20 -> blocked.
                     */
                    if ((float) $lockedRawMaterial->stock < $stockQty) {
                        throw new \RuntimeException(
                            "Insufficient {$lockedRawMaterial->name} stock to complete this return."
                        );
                    }

                    $lockedRawMaterial->decrement(
                        'stock',
                        $stockQty
                    );

                    $returnTotal += $lineTotal;
                }

                /*
                 * Physical return has now affected inventory.
                 */
                $purchaseReturn->update([
                    'status' => 'completed',
                ]);

                /*
                 * Finance handling:
                 *
                 * If a Vendor Bill already exists, create a Vendor Credit.
                 *
                 * If there is NO bill yet, don't create credit.
                 * Vendor Bill generation will later use net received qty.
                 */
                $vendorBill = VendorBill::query()
                    ->where(
                        'purchase_order_id',
                        $lockedReceipt->purchase_order_id
                    )
                    ->first();

                if ($vendorBill) {

                    VendorCredit::create([
                        'purchase_return_id' => $purchaseReturn->id,
                        'vendor_id' => $lockedReceipt
                            ->purchaseOrder
                            ->vendor_id,

                        'vendor_bill_id' => $vendorBill->id,

                        'credit_number' => $this->generateCreditNumber(),

                        'credit_date' => $validated['return_date'],

                        'amount' => round($returnTotal, 2),

                        'applied_amount' => 0,
                        'refunded_amount' => 0,

                        'status' => 'open',

                        'notes' =>
                        "Generated from purchase return {$purchaseReturn->return_number}.",
                    ]);
                }

                return $purchaseReturn;
            });

            return response()->json([
                'success' => true,
                'message' => 'Materials returned successfully.',
                'redirect' => route(
                    'purchase-returns.show',
                    $purchaseReturn
                ),
            ], 201);
        } catch (\RuntimeException $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        } catch (\Throwable $e) {

            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Unable to process the purchase return.',
            ], 500);
        }
    }


    public function show(PurchaseReturn $purchaseReturn)
    {
        $purchaseReturn->load([
            'goodsReceipt.purchaseOrder.vendor',
            'items.goodsReceiptItem.purchaseOrderItem.rawMaterial',
            'items.unit',
            'vendorCredit.applications.vendorBill',
        ]);

        return view(
            'purchase_returns.show',
            compact('purchaseReturn')
        );
    }


    private function generateReturnNumber(): string
    {
        return 'RET-' . str_pad(
            (PurchaseReturn::max('id') ?? 0) + 1,
            5,
            '0',
            STR_PAD_LEFT
        );
    }


    private function generateCreditNumber(): string
    {
        return 'VC-' . str_pad(
            (VendorCredit::max('id') ?? 0) + 1,
            5,
            '0',
            STR_PAD_LEFT
        );
    }
}
