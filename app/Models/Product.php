<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'name',
        'sku',
        'unit_id',
        'cost_price',
        'sale_price',
        'stock',
        'minimum_stock',
        'description',
       
    ];

    protected $casts = [
        'cost_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
       
    ];

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }
    public function manufacturingFormulas(): HasMany
    {
        return $this->hasMany(ManufacturingFormula::class);
    }
}