<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Services\PurchaseRequestActivityService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    public function __construct(private PurchaseRequestActivityService $activity) {}
    /**
     * Display purchase orders.
     */
    public function index()
    {
        $orders = PurchaseOrder::with(['vendor'])->latest()->paginate(15);
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
            'purchaseRequest.activities',
            'purchaseRequest.activities.user',
            'purchaseRequest.activities.vendor',
            'items.rawMaterial',
            'items.unit',
            'goodsReceipts.items',
            'comparison.purchaseRequest',
            'comparison.items.purchaseRequest',
            'comparison.items.rawMaterial',
            'comparison.items.unit',
        ]);

        return view('purchase_orders.show', compact('purchaseOrder'));
    }

    /**
     * Delete purchase order.
     */

    public function destroy(PurchaseOrder $purchaseOrder)
    {
        try {
            DB::transaction(function () use ($purchaseOrder) {

                if ($purchaseOrder->purchaseRequest) {
                    $purchaseOrder->purchaseRequest()->update([
                        'status' => 'sent',
                        'stage' => 'confirmation'
                    ]);
                }

                // Activity Logging
                $this->activity->log(
                    $purchaseOrder->purchaseRequest,
                    Auth::user(),
                    $purchaseOrder->vendor,
                    "Purchase Order Deleted.",
                    "Purchase Order was deleted from purchase request number: {$purchaseOrder->purchaseRequest->request_number}.",
                );

                // Delete the purchase order
                $purchaseOrder->delete();
            });

            return response()->json([
                'success' => true,
                'message' => 'Purchase order deleted successfully.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to delete purchase order.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
    public function receiveMaterials()
    {
        $purchaseOrders = PurchaseOrder::whereIn('status', ['partially_received', 'placed'])->latest()->paginate(10);
        return view('raw_materials.receive_materials', compact('purchaseOrders'));
    }
}
