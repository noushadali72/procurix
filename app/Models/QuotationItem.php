<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuotationItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'quotation_id',
        'raw_material_id',
        'qty',
        'unit_id',
        'price',
        'total',
    ];

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    public function rawMaterial()
    {
        return $this->belongsTo(RawMaterial::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}