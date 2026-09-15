<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ManufacturingFormulaItem extends Model
{
    protected $fillable = [
        'manufacturing_formula_id',
        'raw_material_id',
        'quantity',
        'unit_id',
    ];

    public function formula(): BelongsTo
    {
        return $this->belongsTo(
            ManufacturingFormula::class,
            'manufacturing_formula_id'
        );
    }

    public function rawMaterial(): BelongsTo
    {
        return $this->belongsTo(RawMaterial::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }
}