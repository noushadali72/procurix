<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\VendorBill;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

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

        return view('vendor_bills.index', compact('vendorBills'));
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

    public function generatePdf(VendorBill $vendorBill){
        $vendorBill->load(['vendor','purchaseOrder','items','items.rawMaterial','items.unit']);
        $pdf = Pdf::loadView('vendor_bills.pdf',['vendorBill'=>$vendorBill]);
        return $pdf->download($vendorBill->bill_number.'.pdf');
    }


}