<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
#[Fillable(['name','slug','description','inventory_account_id','purchase_account_id', 'sales_account_id','costing_method'])]
class Category extends Model
{
    protected $casts = [
        'costing_method' => \App\Enums\CostingMethod::class,
    ];
    public function rawMaterials(){
        return $this->hasMany(RawMaterial::class);
    }
    public function products(){
        return $this->hasMany(Product::class);
    }
    public function inventoryAccount(){
        return $this->belongsTo(Account::class,'inventory_account_id');
    }
    public function purchaseAccount(){
        return $this->belongsTo(Account::class,'purchase_account_id');
    }
    public function salesAccount(){
        return $this->belongsTo(Account::class,'sales_account_id');
    }
}
