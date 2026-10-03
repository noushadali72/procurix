<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
#[Fillable(['product_id','raw_material_id','qty','direction','type','reference_type','reference_id','created_by','unit_id','notes'])]
class StockMovement extends Model
{
    protected $casts = [
        'qty'=>'decimal:2'
    ];
    public function product(){
        return $this->belongsTo(Product::class);
    }
    public function rawMaterial(){
        return $this->belongsTo(RawMaterial::class);
    }
    public function unit(){
        return $this->belongsTo(Unit::class);
    }
    public function reference(){
        return $this->morphTo();
    }
    public function creator(){
        return $this->belongsTo(User::class,'created_by');
    }
}
