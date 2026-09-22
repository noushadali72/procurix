<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\VendorBill;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendorBillController extends Controller
{
    /**
     * Display purchase orders for vendor bills.
     */

    public function index()
    {
        $vendorBills = VendorBill::with([
            'vendor',
            'purchaseOrder',
        ])->latest()->paginate(10);

        $purchaseOrders = PurchaseOrder::with('vendor')
            ->where('status', 'received')
            ->whereDoesntHave('vendorBill')
            ->latest()
            ->get();

        return view('vendor_bills.index', compact(
            'vendorBills',
            'purchaseOrders'
        ));
    }


    public function store(Request $request)
    {
        $request->validate([
            'purchase_order_id' => [
                'required',
                'exists:purchase_orders,id',
            ],
        ]);

        $purchaseOrder = PurchaseOrder::findOrFail(
            $request->purchase_order_id
        );

        if ($purchaseOrder->status !== 'received') {
            return response()->json([
                'message' => 'Vendor bill can only be generated for a received purchase order.'
            ], 422);
        }

        if ($purchaseOrder->vendorBill) {
            return response()->json([
                'message' => 'Vendor bill already exists for this purchase order.'
            ], 422);
        }

        $purchaseOrder->load('items');

        $total = 0;
        $vendorBill = null;

        DB::transaction(function() use($purchaseOrder,&$vendorBill,&$total){
        $vendorBill = VendorBill::create([
            'purchase_order_id' => $purchaseOrder->id,
            'vendor_id' => $purchaseOrder->vendor_id,
            'bill_date' => now()->toDateString(),
            'due_date' => now()->addDays(3)->toDateString(),
           
            'subtotal' => 0,
            'tax' => 0,
            'total' => 0,
            'status' => 'unpaid',
            'notes' => '',
        ]);

        foreach ($purchaseOrder->items as $item) {
            
            $lineTotal = $item->qty * $item->unit_cost;
            
            $total += $lineTotal;

            $vendorBill->items()->create([
                'raw_material_id' => $item->raw_material_id,
                'qty' => $item->qty,
                'unit_id' => $item->unit_id,
                'unit_cost' => $item->unit_cost,
                'line_total' => $lineTotal,
            ]);
        }

        $vendorBill->update([
            'subtotal' => $total,
            'total' => $total,
           
        ]);

        });

        return response()->json([
            'message' => 'Vendor bill generated successfully.',
            'redirect' => route('vendor-bills.show', $vendorBill),
        ]);
    }



    /**
     * Display a purchase order.
     */
    public function show(VendorBill $vendorBill)
    {
        $vendorBill->load([
            'vendor',
            'items',
            'items.rawMaterial',
            'items.unit',
            'purchaseOrder'
        ]);

        return view('vendor_bills.show', compact('vendorBill'));
    }

    /**
     * Generate a vendor bill from a received purchase order.
     */

    public function generate(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->status !== 'received') {
            return response()->json([
                'message' => 'Vendor bill can only be generated for a received purchase order.'
            ], 422);
        }

        if ($purchaseOrder->vendorBill) {
            return response()->json([
                'message' => 'Vendor bill already exists for this purchase order.'
            ], 422);
        }

        $purchaseOrder->load('items');

        $total = 0;

        $vendorBill = VendorBill::create([
            'purchase_order_id' => $purchaseOrder->id,
            'vendor_id' => $purchaseOrder->vendor_id,
            'bill_date' => now()->toDateString(),
            'due_date' => now()->addDays(3)->toDateString(),
            
            'subtotal' => 0,
            'tax' => 0,
            'total' => 0,
            'status' => 'unpaid',
            'notes' => '',
        ]);

        foreach ($purchaseOrder->items as $item) {
            $lineTotal = $item->qty * $item->price;
            $total += $lineTotal;

            $vendorBill->items()->create([
                'raw_material_id' => $item->raw_material_id,
                'qty' => $item->qty,
                'unit_id' => $item->unit_id,
                'unit_price' => $item->price,
                'line_total' => $lineTotal,
            ]);
        }

        $vendorBill->update([
            'subtotal' => $total,
            'total' => $total,
           
        ]);

        return response()->json([
            'message' => 'Vendor bill generated successfully.'
        ]);
    }

    public function generatePdf(VendorBill $vendorBill)
    {
        $vendorBill->load(['vendor', 'purchaseOrder', 'items', 'items.rawMaterial', 'items.unit']);
        $pdf = Pdf::loadView('vendor_bills.pdf', ['vendorBill' => $vendorBill]);
        return $pdf->download($vendorBill->bill_number . '.pdf');
    }
}
