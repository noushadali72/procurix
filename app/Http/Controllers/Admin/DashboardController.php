<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ManufacturingRecord;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseRequest;
use App\Models\Quotation;
use App\Models\RawMaterial;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
        {
            $productsCount = Product::count();
            $rawMaterialCount = RawMaterial::count();
            $quotationsCount = Quotation::whereIn('status', ['active','pending',])->count();
            $purchaseOrdersCount = PurchaseOrder::count();
            $purchaseRequestsCount = PurchaseRequest::count();
            $manufacturingCount = ManufacturingRecord::count();

            $lowStockMaterials = RawMaterial::with('unit')->whereColumn('stock', '<=', 'minimum_stock')
                ->latest()
                ->take(5)
                ->get();

            $manufacturingRecords = ManufacturingRecord::with([
                'product',
                'manufacturingFormula',
                'unit',
            ])->latest('manufactured_at')->take(5)->get();

            return view('admin.dashboard', compact(
                'productsCount',
                'rawMaterialCount',
                'quotationsCount',
                'purchaseOrdersCount',
                'purchaseRequestsCount',
                'manufacturingCount',
                'lowStockMaterials',
                'manufacturingRecords'
            ));
        }
}
