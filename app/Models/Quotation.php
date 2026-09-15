<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Quotation extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_request_id',
        'vendor_id',
        'quotation_number',
        'status',
        'quotation_date',
        'valid_until',
        'notes',
    ];

    protected $casts = [
        'quotation_date' => 'date',
        'valid_until' => 'date',
    ];

    public function purchaseRequest()
    {
        return $this->belongsTo(PurchaseRequest::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function items()
    {
        return $this->hasMany(QuotationItem::class);
    }
    public function purchaseOrder()
    {
        return $this->hasOne(PurchaseOrder::class);
    }
}