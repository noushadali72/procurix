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
        'purchaseRequest',
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
        try{

            $purchaseOrder->delete();
            return response()->json(['message' => 'Purchase order deleted successfully.'], 200);

        }catch(\Exception $e){
            return response()->json(['message' => 'Unable to delete purchase order.'], 500);
        }
    }

    public function receiveMaterials(){
        $purchaseOrders = PurchaseOrder::whereIn('status',['partially_received','placed'])->latest()->paginate(10);
        return view('raw_materials.receive_materials',compact('purchaseOrders'));
    }
}