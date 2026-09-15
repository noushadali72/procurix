<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;

class PurchaseOrderController extends Controller
{
    /**
     * Display purchase orders.
     */
    public function index()
    {
        $orders = PurchaseOrder::with([
            'vendor',
            'quotation',
        ])
            ->latest()
            ->paginate(15);

        return view('purchase_orders.index', compact('orders'));
    }

    /**
     * Display purchase order.
     */
    public function show(PurchaseOrder $purchaseOrder)
    {
       $purchaseOrder->load([
        'vendor',
        'quotation.purchaseRequest',
        'items.rawMaterial',
        'items.unit',
        'goodsReceipts.items',
    ]);

    return view('purchase_orders.show', compact('purchaseOrder'));
    }

    /**
     * Delete purchase order.
     */
    public function destroy(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->delete();

        return redirect()
            ->route('purchase-orders.index')
            ->with(
                'success',
                'Purchase order deleted successfully.'
            );
    }
}