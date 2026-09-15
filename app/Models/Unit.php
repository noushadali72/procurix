<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $fillable = [
        'name',
        'short_name',
        'unit_category_id',
        'is_base',
        'conversion_factor',
    ];

    public function unitCategory()
    {
        return $this->belongsTo(UnitCategory::class);
    }
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function rawMaterials()
    {
        return $this->hasMany(RawMaterial::class);
    }

    public function manufacturingFormulaItems()
    {
        return $this->hasMany(ManufacturingFormulaItem::class);
    }

    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function purchaseRequestItems()
    {
        return $this->hasMany(PurchaseRequestItem::class);
    }

    public function manufacturingRecords()
    {
        return $this->hasMany(ManufacturingRecord::class);
    }

}