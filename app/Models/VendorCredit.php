<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VendorCredit extends Model
{
    protected $fillable = [
        'purchase_return_id',
        'vendor_id',
        'vendor_bill_id',
        'credit_number',
        'credit_date',
        'amount',
        'status',
        'notes',
    ];

    protected $casts = [
        'credit_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function purchaseReturn(): BelongsTo
    {
        return $this->belongsTo(PurchaseReturn::class);
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
     * Original/source bill that caused this credit.
     */
    public function vendorBill(): BelongsTo
    {
        return $this->belongsTo(VendorBill::class);
    }

    /**
     * Bills this credit has been applied against.
     */
    public function applications(): HasMany
    {
        return $this->hasMany(
            VendorCreditApplication::class
        );
    }

    /**
     * Actual refunds received from vendor.
     */
    public function refunds(): HasMany
    {
        return $this->hasMany(
            VendorCreditRefund::class
        );
    }

    public function getAppliedAmountAttribute()
    {
        return $this->applications()
            ->sum('amount');
    }

    public function getRefundedAmountAttribute()
    {
        return $this->refunds()
            ->sum('amount');
    }

    public function getRemainingAmountAttribute()
    {
        return max(
            (float) $this->amount
            - (float) $this->applied_amount
            - (float) $this->refunded_amount,
            0
        );
    }
}