<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GoodsReceiptController;
use App\Http\Controllers\ManufacturingController;
use App\Http\Controllers\ManufacturingFormulaController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PaymentTermController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\PurchaseRequestActivityController;
use App\Http\Controllers\PurchaseRequestController;
use App\Http\Controllers\PurchaseReturnController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\RawMaterialController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VendorBillController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\VendorCreditController;
use App\Http\Controllers\VendorPaymentController;
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

    // User management
    Route::resource('users', UserController::class)->except(['edit', 'show']);

    // Role Management
    Route::resource('roles', RoleController::class)->except(['edit', 'show']);

    // Permission management
    Route::resource('permissions', PermissionController::class)->except(['edit', 'show']);

    // Inventory routes
    Route::resource('products', ProductController::class);
    Route::resource('raw-materials', RawMaterialController::class)->except(['show']);
    Route::resource('units', UnitController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('warehouses', WarehouseController::class)->only(['index', 'store', 'update', 'destroy']);

    // Procurement
    Route::get('/purchase-requests/raw-material/{rawMaterial}', [PurchaseRequestController::class, 'rawMaterial'])->name('purchase-requests.raw-material');
    Route::resource('purchase-requests', PurchaseRequestController::class);
    Route::post('purchase-requests/{purchaseRequest}/update-status', [PurchaseRequestController::class, 'updateStatus'])->name('purchase-requests.updateStatus');
    Route::post('purchase-requests/{purchaseRequest}/confirm', [PurchaseRequestController::class, 'confirm'])->name('purchase-requests.confirm');
    Route::get('purchase-requests/{purchaseRequest}/confirmation', [PurchaseRequestController::class, 'confirmation'])->name('purchase-requests.confirmation');
    Route::post('purchase-requests/save-draft', [PurchaseRequestController::class, 'saveDraft'])->name('purchase-requests.save-draft');
    Route::post('purchase-requests/{pr}/duplicate', [PurchaseRequestController::class, 'duplicate'])->name('purchase-requests.duplicate');
    // Route::get('purchase-requests/{purchaseRequest}/compare',[PurchaseRequestController::class, 'compare'])->name('purchase-requests.compare');
    Route::get('purchase-requests/{purchaseRequest}/compare', [PurchaseRequestController::class, 'compare'])->name('purchase-requests.compare');
    Route::post('purchase-requests/{purchaseRequest}/compare/confirm', [PurchaseRequestController::class, 'confirmComparison'])->name('purchase-requests.compare.confirm');
    Route::post('purchase-requests/{purchaseRequest}/resend-rfq', [PurchaseRequestController::class, 'resendRfq'])->name('purchase-requests.resend-rfq');
    Route::resource('vendors', VendorController::class);
    Route::resource('quotations', QuotationController::class)->except('create');
    Route::get('quotations/create/{purchaseRequest}', [QuotationController::class, 'create'])->name('quotations.create');
    Route::post('quotations/{quotation}/accept', [QuotationController::class, 'accept'])->name('quotations.accept');
    Route::resource('purchase-orders', PurchaseOrderController::class)->only(['index', 'show', 'destroy']);
    Route::post('purchase-orders/{purchaseOrder}/receive', [PurchaseOrderController::class, 'receive'])->name('purchase-orders.receive');
    Route::get('purchase-requests/{pr}/quotations', [PurchaseRequestController::class, 'quotations'])->name('purchase-requests.quotations');
    Route::get('purchase-requests/{pr}/quotations/compare', [PurchaseRequestController::class, 'compareQuotations'])->name('purchase-requests.quotations.compare');

    // Receiving
    Route::get('goods-receipts', [GoodsReceiptController::class, 'index'])->name('goods-receipts.index');
    Route::get('goods-receipts/{goodsReceipt}', [GoodsReceiptController::class, 'show'])->name('goods-receipts.show');
    Route::get('purchase-orders/{purchaseOrder}/goods-receipts/create', [GoodsReceiptController::class, 'create'])->name('goods-receipts.create');
    Route::post('purchase-orders/{purchaseOrder}/goods-receipts', [GoodsReceiptController::class, 'store'])->name('goods-receipts.store');
    Route::get('materials/receive-materials', [PurchaseOrderController::class, 'receiveMaterials'])->name('materials.receive');
    Route::delete('goods-receipts/{goodsReceipt}', [GoodsReceiptController::class, 'destroy'])->name('goods-receipts.destroy');
    Route::delete('goods-receipt-attachments/{attachment}', [GoodsReceiptController::class, 'destroyAttachment'])->name('goods-receipt-attachments.destroy');

    // Manufacturing
    Route::resource('manufacturing-formulas', ManufacturingFormulaController::class)->except(['show']);
    Route::post('/manufacturing/manufacture', [ManufacturingController::class, 'manufacture'])->name('manufacturing.manufacture');
    Route::get('/manufacturing', [ManufacturingController::class, 'index'])->name('manufacturing.index');
    Route::get('/manufacturing/records', [ManufacturingController::class, 'records'])->name('manufacturing.records');
    Route::post('/manufacturing/manufacture', [ManufacturingController::class, 'manufacture'])->name('manufacturing.manufacture');
    Route::post('manufacturing/autosave', [ManufacturingController::class, 'autoSave'])->name('manufacturing.autosave');


    // Purchase Returns

    Route::get(
        'purchase-returns',
        [PurchaseReturnController::class, 'index']
    )->name('purchase-returns.index');

    Route::get(
        'purchase-returns/{purchaseReturn}',
        [PurchaseReturnController::class, 'show']
    )->name('purchase-returns.show');

    Route::get(
        'goods-receipts/{goodsReceipt}/returns/create',
        [PurchaseReturnController::class, 'create']
    )->name('purchase-returns.create');

    Route::post(
        'goods-receipts/{goodsReceipt}/returns',
        [PurchaseReturnController::class, 'store']
    )->name('purchase-returns.store');



    // Finance
    Route::resource('vendor-bills', VendorBillController::class)->except('create', 'show');
    Route::get('vendor-bills/{purchaseOrder}/create', [VendorBillController::class, 'create'])->name('vendor-bills.create');
    Route::get('vendor-bills/{vendorBill}', [VendorBillController::class, 'show'])->name('vendor-bills.show');
    Route::post('vendors-bills/generate/{purchaseOrder}', [VendorBillController::class, 'generate'])->name('vendor-bills.generate');
    Route::get('vendor-bills/generate-pdf/{vendorBill}', [VendorBillController::class, 'generatePdf'])->name('vendor-bills.generatepdf');
    Route::resource('vendor-payments', VendorPaymentController::class)->except('store');
    Route::post('vendor-payments/{vendorBill}', [VendorPaymentController::class, 'store'])->name('vendor-payments.store');
    Route::resource('payment-terms', PaymentTermController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::view('test', 'test.index');


    // Vendor Credits

    Route::get(
        'vendor-credits',
        [VendorCreditController::class, 'index']
    )->name('vendor-credits.index');

    Route::get(
        'vendor-credits/{vendorCredit}',
        [VendorCreditController::class, 'show']
    )->name('vendor-credits.show');

    Route::post(
        'vendor-credits/{vendorCredit}/apply',
        [VendorCreditController::class, 'apply']
    )->name('vendor-credits.apply');

    Route::post(
        'vendor-credits/{vendorCredit}/refund',
        [VendorCreditController::class, 'refund']
    )->name('vendor-credits.refund');


    // Activiteis
    Route::get('purchase-request-activities/{purchaseRequest}',[PurchaseRequestActivityController::class,'index'])->name('purchase-requests.activities.index');
    // Logout
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});
