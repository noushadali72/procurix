<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $fillable = [
        'order_number',
        'quotation_id',
        'purchase_request_id',
        'vendor_id',
        'status',
        'order_date',
        'received_date',
        'notes',
        'total'
    ];

    protected $casts = [
        'order_date' => 'date',
        'received_date' => 'date',
    ];

    public function purchaseRequest(){
        return $this->belongsTo(PurchaseRequest::class,'purchase_request_id');
    }

    public function quotation()
    {
        return $this->belongsTo(Quotation::class, 'quotation_id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function goodsReceipts()
    {
        return $this->hasMany(GoodsReceipt::class);
    }

    public function vendorBill(){
        return $this->hasOne(VendorBill::class);
    }


}