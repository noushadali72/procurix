<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
#[Fillable(['name','slug','description'])]
class Category extends Model
{
    public function rawMaterials(){
        return $this->hasMany(RawMaterial::class);
    }
    public function products(){
        return $this->hasMany(Product::class);
    }
}
