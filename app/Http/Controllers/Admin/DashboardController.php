<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ManufacturingRecord;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseRequest;
use App\Models\PurchaseReturn;
use App\Models\RawMaterial;
use App\Models\VendorBill;
use App\Models\VendorCredit;
use App\Models\GoodsReceipt;

class DashboardController extends Controller
{
    public function dashboard()
    {
        /*
         * Basic counts
         */
        $productsCount = Product::count();

        $rawMaterialCount = RawMaterial::count();

        $purchaseRequestsCount = PurchaseRequest::count();

        $purchaseOrdersCount = PurchaseOrder::count();

        $lowStockCount = RawMaterial::whereColumn(
            'stock',
            '<=',
            'minimum_stock'
        )->count();


        /*
         * Procurement overview
         */
        $goodsReceiptsCount = GoodsReceipt::count();

        $purchaseReturnsCount = PurchaseReturn::count();

        $pendingPurchaseReturnsCount = PurchaseReturn::where(
            'status',
            'draft'
        )->count();


        /*
         * Vendor bills
         *
         * Only successful payments reduce the outstanding amount.
         */
        $outstandingBills = VendorBill::query()
            ->whereIn('status', [
                'unpaid',
                'partially_paid',
                'pending',
                'overdue',
            ])
            ->withSum([
                'vendorPayments as paid_amount' => function ($query) {
                    $query->where('status', 'successful');
                },
            ], 'amount')
            ->get();

        $outstandingBillsCount = $outstandingBills->count();

        $outstandingBillsAmount = $outstandingBills->sum(function ($bill) {
            return max(
                (float) $bill->total - (float) ($bill->paid_amount ?? 0),
                0
            );
        });


        /*
         * Vendor credits
         */
        $vendorCredits = VendorCredit::query()
            ->whereIn('status', [
                'open',
                'partially_applied',
            ])
            ->get();

        $availableCreditsCount = $vendorCredits->count();

        $availableCreditsAmount = $vendorCredits->sum(function ($credit) {
            return max(
                (float) $credit->amount
                - (float) $credit->applied_amount
                - (float) $credit->refunded_amount,
                0
            );
        });


        /*
         * Low stock materials
         */
        $lowStockMaterials = RawMaterial::with('unit')
            ->whereColumn('stock', '<=', 'minimum_stock')
            ->latest()
            ->take(5)
            ->get();


        /*
         * Recent manufacturing
         */
        $manufacturingRecords = ManufacturingRecord::with([
            'product',
            'manufacturingFormula',
            'unit',
        ])
            ->latest('manufactured_at')
            ->take(5)
            ->get();


        /*
         * Recent procurement activity
         *
         * Purchase request activities represent the complete
         * procurement lifecycle.
         */
        $recentActivities = \App\Models\PurchaseRequestActivity::with([
            'purchaseRequest',
            'user',
            'vendor',
        ])
            ->latest()
            ->take(8)
            ->get();


        return view('admin.dashboard', compact(
            'productsCount',
            'rawMaterialCount',
            'purchaseRequestsCount',
            'purchaseOrdersCount',
            'lowStockCount',

            'goodsReceiptsCount',
            'purchaseReturnsCount',
            'pendingPurchaseReturnsCount',

            'outstandingBillsCount',
            'outstandingBillsAmount',

            'availableCreditsCount',
            'availableCreditsAmount',

            'lowStockMaterials',
            'manufacturingRecords',
            'recentActivities',
        ));
    }
}