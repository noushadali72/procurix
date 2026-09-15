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
            'items.goodsReceiptItems',
        ]);

        return view('goods_receipts.create', compact('purchaseOrder'));
    }

    public function store(
        StoreGoodsReceiptRequest $request,
        PurchaseOrder $purchaseOrder,
        UnitConversionService $conversion
    ): JsonResponse {
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
                    ])
                    ->findOrFail($item['purchase_order_item_id']);

                $unit = Unit::findOrFail($item['unit_id']);

                $receivedQty = $orderItem->goodsReceiptItems()
                    ->with('unit')
                    ->get()
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

                $remainingQty = (float) $orderItem->qty - $receivedQty;

                if ($currentReceivedQty > $remainingQty) {
                    throw new \RuntimeException(
                        "Received quantity for {$orderItem->rawMaterial->name} cannot exceed the remaining quantity."
                    );
                }

                $goodsReceipt->items()->create([
                    'purchase_order_item_id' => $orderItem->id,
                    'qty' => $item['qty'],
                    'unit_id' => $unit->id,
                ]);

                $stockQty = $conversion->convert(
                    (float) $item['qty'],
                    $unit,
                    $orderItem->rawMaterial->unit
                );

                $rawMaterial = RawMaterial::lockForUpdate()
                    ->findOrFail($orderItem->raw_material_id);

                $rawMaterial->increment('stock', $stockQty);
            }

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

            $this->updatePurchaseOrderStatus($purchaseOrder);

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
            'items.purchaseOrderItem.rawMaterial',
            'items.unit',
            'attachments',
        ]);

        return view('goods_receipts.show', compact('goodsReceipt'));
    }

    public function destroy(
        GoodsReceipt $goodsReceipt,
        UnitConversionService $conversion
    ): JsonResponse {
        DB::transaction(function () use ($goodsReceipt, $conversion) {

            $goodsReceipt->load([
                'purchaseOrder',
                'items.purchaseOrderItem.rawMaterial.unit',
                'items.unit',
                'attachments',
            ]);

            $purchaseOrder = $goodsReceipt->purchaseOrder;

            foreach ($goodsReceipt->items as $item) {
                $orderItem = $item->purchaseOrderItem;

                if (!$orderItem || !$orderItem->rawMaterial) {
                    continue;
                }

                $stockQty = $conversion->convert(
                    (float) $item->qty,
                    $item->unit,
                    $orderItem->rawMaterial->unit
                );

                $orderItem->rawMaterial->decrement(
                    'stock',
                    $stockQty
                );
            }

            foreach ($goodsReceipt->attachments as $attachment) {
                if (Storage::disk('public')->exists($attachment->file_path)) {
                    Storage::disk('public')->delete($attachment->file_path);
                }
            }

            $goodsReceipt->delete();

            $this->updatePurchaseOrderStatus($purchaseOrder);
        });

        return response()->json([
            'message' => 'Goods receipt deleted successfully.',
            'redirect' => route('purchase-orders.show', $goodsReceipt->purchase_order_id),
        ]);
    }

    public function destroyAttachment(
        GoodsReceiptAttachment $attachment
    ): JsonResponse {
        $goodsReceipt = $attachment->goodsReceipt;

        if (Storage::disk('public')->exists($attachment->file_path)) {
            Storage::disk('public')->delete($attachment->file_path);
        }

        $attachment->delete();

        return response()->json([
            'message' => 'Attachment deleted successfully.',
            'id' => $attachment->id,
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
        PurchaseOrder $purchaseOrder
    ): void {
        $purchaseOrder->load([
            'items.unit',
            'items.goodsReceiptItems.unit',
        ]);

        $allReceived = true;
        $hasReceived = false;

        foreach ($purchaseOrder->items as $orderItem) {

            $receivedQty = $orderItem->goodsReceiptItems
                ->sum(function ($receiptItem) use ($orderItem) {
                    return app(UnitConversionService::class)->convert(
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
