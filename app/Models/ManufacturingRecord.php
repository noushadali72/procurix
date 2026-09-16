<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ManufacturingRecord extends Model
{
    protected $fillable = [
        'product_id',
        'manufacturing_formula_id',
        'quantity',
        'unit_id',
        'status',
        'manufactured_at',
    ];

    protected $casts = [
        'quantity' => 'decimal:4',
        'manufactured_at' => 'datetime',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function manufacturingFormula(): BelongsTo
    {
        return $this->belongsTo(
            ManufacturingFormula::class,
            'manufacturing_formula_id'
        );
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }
}