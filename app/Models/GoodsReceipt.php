<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GoodsReceipt extends Model
{
    protected $fillable = [
        'purchase_order_id',
        'grn_number',
        'received_date',
        'notes',
    ];

    protected $casts = [
        'received_date' => 'date',
    ];

    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(GoodsReceiptItem::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(GoodsReceiptAttachment::class);
    }
    public function purchaseReturns(): HasMany
    {
        return $this->hasMany(PurchaseReturn::class);
    }
}
