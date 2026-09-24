<?php

namespace App\Models;

use App\Observers\RawMaterialObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ObservedBy(RawMaterialObserver::class)]
class RawMaterial extends Model
{
    protected $fillable = [
        'name',
        'sku',
        'cost_price',
        'stock',
        'unit_id',
        'category_id',
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
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function manufacturingFormulaItems(): HasMany
    {
        return $this->hasMany(ManufacturingFormulaItem::class);
    }

    public function purchaseRequestItems()
    {
        return $this->hasMany(PurchaseRequestItem::class);
    }

    public function hasPurchaseRequest()
    {
        return  PurchaseRequest::where('status', 'draft')

            ->whereHas('items', function ($q) {
                $q->where('raw_material_id', $this->id);
            })->exists();
    }

    public function activities()
    {
        return $this->hasMany(RawMaterialActivity::class);
    }
}
