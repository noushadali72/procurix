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

    protected static function booted()
    {

        static::created(function (PurchaseOrder $order) {
            $order->order_number = 'PO-' . str_pad($order->id, 5, '0', STR_PAD_LEFT);
            $order->saveQuietly();
        });
    }

    public function purchaseRequest()
    {
        return $this->belongsTo(PurchaseRequest::class, 'purchase_request_id');
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

    public function vendorBill()
    {
        return $this->hasOne(VendorBill::class);
    }

    public function comparison()
    {
        return $this->hasOne(RfqComparison::class);
    }

    private static function generateOrderNumber(): string
    {
        return "PO-" . str_pad(((PurchaseOrder::max('id') ?? 0) + 1), 5, '0', STR_PAD_LEFT);
    }
}
