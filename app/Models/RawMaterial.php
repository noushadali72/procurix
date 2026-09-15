<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RawMaterial extends Model
{
    protected $fillable = [
        'name',
        'sku',
        'cost_price',
        'stock',
        'unit_id',
        'minimum_stock',
        'description',
    ];

    protected $casts = [
        'cost_price' => 'decimal:2',
    ];

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }
    public function manufacturingFormulaItems(): HasMany
    {
        return $this->hasMany(ManufacturingFormulaItem::class);
    }

        public function purchaseRequestItems()
        {
            return $this->hasMany(PurchaseRequestItem::class);
        }
}