<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseRequest;
use App\Models\Quotation;
use App\Models\RawMaterial;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard(){
        $productsCount = Product::all()->count();
        $rawMaterialCount = RawMaterial::all()->count();
        $quotationsCount = Quotation::where('status','active')->orWhere('status','pending')->count();
        $purchaseOrdersCount = PurchaseOrder::all()->count();
        $purchaseRequestsCount = PurchaseRequest::all()->count();
        return view('admin.dashboard',compact(
            'productsCount','quotationsCount','rawMaterialCount','purchaseOrdersCount','purchaseRequestsCount'
        ));
    }
}
