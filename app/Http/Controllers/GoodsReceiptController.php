<?php

namespace App\Http\Controllers;

use App\Http\Requests\GoodsReceipt\StoreGoodsReceiptRequest;
use App\Models\GoodsReceipt;
use App\Models\GoodsReceiptAttachment;
use App\Models\PurchaseOrder;
use App\Models\RawMaterial;
use App\Models\Unit;
use App\Services\UnitConversionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class GoodsReceiptController extends Controller
{
    public function index()
    {
        $goodsReceipts = GoodsReceipt::with([
            'purchaseOrder.vendor',
            'items',
        ])
            ->latest()
            ->paginate(15);

        return view('goods_receipts.index', compact('goodsReceipts'));
    }

    public function create(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->status === 'received') {
            return redirect()
                ->route('purchase-orders.show', $purchaseOrder)
                ->with('error', 'This purchase order has already been fully received.');
        }

        if ($purchaseOrder->status === 'cancelled') {
            return redirect()
                ->route('purchase-orders.show', $purchaseOrder)
                ->with('error', 'A cancelled purchase order cannot receive materials.');
        }

        $purchaseOrder->load([
            'vendor',
            'items.rawMaterial.unit.unitCategory',
            'items.unit.unitCategory',
            'items.goodsReceiptItems.unit',
        ]);

        $units = Unit::with('unitCategory')
            ->orderBy('name')
            ->get();

        return view(
            'goods_receipts.create',
            compact('purchaseOrder', 'units')
        );
    }

    public function store(StoreGoodsReceiptRequest $request, PurchaseOrder $purchaseOrder,UnitConversionService $conversion): JsonResponse {
        if ($purchaseOrder->status === 'received') {
            return response()->json([
                'message' => 'This purchase order has already been fully received.',
            ], 422);
        }

        if ($purchaseOrder->status === 'cancelled') {
            return response()->json([
                'message' => 'A cancelled purchase order cannot receive materials.',
            ], 422);
        }

        $validated = $request->validated();

        $goodsReceipt = DB::transaction(function () use (
            $validated,
            $purchaseOrder,
            $request,
            $conversion
        ) {
            $goodsReceipt = GoodsReceipt::create([
                'purchase_order_id' => $purchaseOrder->id,
                'grn_number' => $this->generateGrnNumber(),
                'received_date' => $validated['received_date'],
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($validated['items'] as $item) {

                $orderItem = $purchaseOrder->items()
                    ->with([
                        'rawMaterial.unit',
                        'unit',
                        'goodsReceiptItems.unit',
                    ])
                    ->findOrFail($item['purchase_order_item_id']);

                $unit = Unit::findOrFail($item['unit_id']);

                $receivedQty = $orderItem->goodsReceiptItems
                    ->sum(function ($receiptItem) use ($conversion, $orderItem) {
                        return $conversion->convert(
                            (float) $receiptItem->qty,
                            $receiptItem->unit,
                            $orderItem->unit
                        );
                    });
                $currentReceivedQty = $conversion->convert(
                    (float) $item['qty'],
                    $unit,
                    $orderItem->unit
                );

                /*
                 * Quantity remaining before this receipt.
                 */
                $remainingBeforeReceipt = max(0,(float) $orderItem->qty - $receivedQty);

                /*
                 * Prevent receiving more than the ordered quantity.
                 */
                if ($currentReceivedQty > $remainingBeforeReceipt) {
                    throw new \RuntimeException(
                        "Received quantity for {$orderItem->rawMaterial->name} cannot exceed the remaining quantity."
                    );
                }

                /*
                 * Quantity remaining after this receipt.
                 * Stored in the purchase order item's unit.
                 */
                $remainingAfterReceipt = max(0,$remainingBeforeReceipt - $currentReceivedQty);

                $goodsReceipt->items()->create([
                    'purchase_order_item_id' => $orderItem->id,
                    'qty' => $item['qty'],
                    'remaining_qty' => $remainingAfterReceipt,
                    'unit_id' => $unit->id,
                ]);

                /*
                 * Add received quantity to raw material stock.
                 * Stock is always stored in the raw material's stock unit.
                 */
                $stockQty = $conversion->convert(
                    (float) $item['qty'],
                    $unit,
                    $orderItem->rawMaterial->unit
                );
                $rawMaterial = RawMaterial::lockForUpdate()->findOrFail($orderItem->raw_material_id);
                $rawMaterial->increment('stock', $stockQty);
            }

            /*
             * Store attachments.
             */
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store(
                        'goods-receipts/' . $goodsReceipt->id,
                        'public'
                    );
                    $goodsReceipt->attachments()->create([
                        'file_path' => $path,
                    ]);
                }
            }

            /*
             * Update purchase order status.
             */
            $this->updatePurchaseOrderStatus($purchaseOrder, $conversion);
            return $goodsReceipt;
        });

        return response()->json([
            'message' => 'Goods receipt created successfully.',
            'id' => $goodsReceipt->id,
            'redirect' => route('goods-receipts.show', $goodsReceipt),
        ], 201);
    }

    public function show(GoodsReceipt $goodsReceipt)
    {
        $goodsReceipt->load([
            'purchaseOrder.vendor',
            'purchaseOrder.items.rawMaterial',
            'purchaseOrder.items.unit',
            'items.purchaseOrderItem.rawMaterial',
            'items.purchaseOrderItem.unit',
            'items.unit',
            'attachments',
        ]);

        return view(
            'goods_receipts.show',
            compact('goodsReceipt')
        );
    }

    public function destroy(GoodsReceipt $goodsReceipt): JsonResponse
    {
        try {
            $attachments = $goodsReceipt->attachments;

            $goodsReceipt->delete();

            foreach ($attachments as $attachment) {
                Storage::disk('public')->delete($attachment->file_path);
            }

            return response()->json([
                'message' => 'Goods receipt deleted successfully.',
                'redirect' => route('goods-receipts.index')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Unable to delete goods receipt.',
            ], 500);
        }
    }
    public function destroyAttachment(
        GoodsReceiptAttachment $attachment
    ): JsonResponse {
        if (Storage::disk('public')->exists($attachment->file_path)) {
            Storage::disk('public')->delete($attachment->file_path);
        }

        $attachmentId = $attachment->id;

        $attachment->delete();

        return response()->json([
            'message' => 'Attachment deleted successfully.',
            'id' => $attachmentId,
        ]);
    }

    private function generateGrnNumber(): string
    {
        return 'GRN-' . str_pad(
            (GoodsReceipt::max('id') ?? 0) + 1,
            5,
            '0',
            STR_PAD_LEFT
        );
    }

    private function updatePurchaseOrderStatus(
        PurchaseOrder $purchaseOrder,
        UnitConversionService $conversion
    ): void {
        $purchaseOrder->load([
            'items.unit',
            'items.goodsReceiptItems.unit',
        ]);

        $allReceived = true;
        $hasReceived = false;

        foreach ($purchaseOrder->items as $orderItem) {

            /*
             * Sum actual received quantities,
             * converting everything to the PO item's unit.
             */
            $receivedQty = $orderItem->goodsReceiptItems
                ->sum(function ($receiptItem) use ($orderItem, $conversion) {
                    return $conversion->convert(
                        (float) $receiptItem->qty,
                        $receiptItem->unit,
                        $orderItem->unit
                    );
                });

            if ($receivedQty > 0) {
                $hasReceived = true;
            }

            if ($receivedQty < (float) $orderItem->qty) {
                $allReceived = false;
            }
        }

        if ($allReceived && $hasReceived) {
            $purchaseOrder->update([
                'status' => 'received',
                'received_date' => now()->toDateString(),
            ]);

            return;
        }

        if ($hasReceived) {
            $purchaseOrder->update([
                'status' => 'partially_received',
                'received_date' => null,
            ]);

            return;
        }

        $purchaseOrder->update([
            'status' => 'placed',
            'received_date' => null,
        ]);
    }
}