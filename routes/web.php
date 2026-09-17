<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GoodsReceiptController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ManufacturingController;
use App\Http\Controllers\ManufacturingFormulaController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\PurchaseRequestController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\RawMaterialController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\VendorBillController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\VendorPaymentController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Auth Routes

Route::middleware(['guest'])->prefix('auth')->group(function () {
    Route::get('/login', [AuthController::class, 'loginView'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
    Route::get('/register', [AuthController::class, 'registerView'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'dashboard'])->name('admin.dashboard');
    Route::resource('products', ProductController::class);
    Route::resource('raw-materials', RawMaterialController::class)->except(['show']);
    Route::resource('manufacturing-formulas', ManufacturingFormulaController::class)->except(['show']);
    Route::resource('units', UnitController::class);
    Route::resource('categories',CategoryController::class);
    Route::get('/purchase-requests/raw-material/{rawMaterial}', [PurchaseRequestController::class, 'rawMaterial'])->name('purchase-requests.raw-material');
    Route::resource('purchase-requests', PurchaseRequestController::class);

    Route::resource('vendors', VendorController::class);

    Route::resource('quotations', QuotationController::class)->except('create');
    Route::get('quotations/create/{purchaseRequest}', [QuotationController::class, 'create'])->name('quotations.create');
    Route::post('quotations/{quotation}/accept', [QuotationController::class, 'accept'])->name('quotations.accept');

    Route::resource('purchase-orders', PurchaseOrderController::class)->only(['index', 'show', 'destroy']);

    Route::post(
        'purchase-orders/{purchaseOrder}/receive',
        [PurchaseOrderController::class, 'receive']
    )->name('purchase-orders.receive');

    Route::get(
        'purchase-orders/{purchaseOrder}/goods-receipts/create',
        [GoodsReceiptController::class, 'create']
    )->name('goods-receipts.create');

    Route::post(
        'purchase-orders/{purchaseOrder}/goods-receipts',
        [GoodsReceiptController::class, 'store']
    )->name('goods-receipts.store');

    Route::get(
        'goods-receipts',
        [GoodsReceiptController::class, 'index']
    )->name('goods-receipts.index');

    Route::get(
        'goods-receipts/{goodsReceipt}',
        [GoodsReceiptController::class, 'show']
    )->name('goods-receipts.show');

    Route::delete('goods-receipts/{goodsReceipt}',[GoodsReceiptController::class, 'destroy'])
    ->name('goods-receipts.destroy');

    Route::delete('goods-receipt-attachments/{attachment}',[GoodsReceiptController::class, 'destroyAttachment'])
    ->name('goods-receipt-attachments.destroy');

    Route::post('/manufacturing/manufacture',[ManufacturingController::class, 'manufacture'])
    ->name('manufacturing.manufacture');

    Route::get('/manufacturing', [ManufacturingController::class, 'index'])
    ->name('manufacturing.index');

    Route::get('/manufacturing/records', [ManufacturingController::class, 'records'])
        ->name('manufacturing.records');

    Route::post('/manufacturing/manufacture', [ManufacturingController::class, 'manufacture'])
        ->name('manufacturing.manufacture');

    Route::post('manufacturing/autosave',[ManufacturingController::class,'autoSave'])->name('manufacturing.autosave');


    // Vendor Billing
    Route::resource('vendor-bills',VendorBillController::class)->except('create','show');
    Route::get('vendor-bills/{purchaseOrder}/create',[VendorBillController::class,'create'])->name('vendor-bills.create');
    Route::get('vendor-bills/{vendorBill}',[VendorBillController::class,'show'])->name('vendor-bills.show');
    Route::post('vendors-bills/generate/{purchaseOrder}',[VendorBillController::class,'generate'])->name('vendor-bills.generate');
    Route::get('vendor-bills/generate-pdf/{vendorBill}',[VendorBillController::class,'generatePdf'])->name('vendor-bills.generatepdf');
    Route::resource('vendor-payments',VendorPaymentController::class)->except('store');
    Route::post('vendor-payments/{vendorBill}',[VendorPaymentController::class,'store'])->name('vendor-payments.store');




    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});
