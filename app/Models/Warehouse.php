<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $fillable = [
        'name',
        'description',
        'is_active',
        'address',
        'city',
        'postal_code',
        'country',
        'phone_no',
        'capacity',
    ];
    public function rawMaterials(){
        return $this->hasMany(RawMaterial::class);
    }
    public function products(){
        return $this->hasMany(Product::class);
    }
}
