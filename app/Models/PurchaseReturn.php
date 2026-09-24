<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseReturn extends Model
{
    protected $fillable = [
        'goods_receipt_id',
        'return_number',
        'return_date',
        'status',
        'reason',
        'notes',
    ];

    protected $casts = [
        'return_date' => 'date',
    ];

    public function goodsReceipt(): BelongsTo
    {
        return $this->belongsTo(GoodsReceipt::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseReturnItem::class);
    }

    public function vendorCredit()
    {
        return $this->hasOne(VendorCredit::class);
    }
}