<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorCreditApplication extends Model
{
    protected $fillable = [
        'vendor_credit_id',
        'vendor_bill_id',
        'amount',
        'applied_date',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'applied_date' => 'date',
    ];

    public function vendorCredit(): BelongsTo
    {
        return $this->belongsTo(VendorCredit::class);
    }

    public function vendorBill(): BelongsTo
    {
        return $this->belongsTo(VendorBill::class);
    }
}