<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorCreditRefund extends Model
{
    protected $fillable = [
        'vendor_credit_id',
        'amount',
        'refund_date',
        'refund_method',
        'transaction_id',
        'reference',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'refund_date' => 'date',
    ];

    public function vendorCredit(): BelongsTo
    {
        return $this->belongsTo(
            VendorCredit::class
        );
    }
}
