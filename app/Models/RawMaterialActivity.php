<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
#[Fillable(['raw_material_id','direction','action','description'])]
class RawMaterialActivity extends Model
{
    public function rawMaterial(){
        return $this->belongsTo(RawMaterial::class);
    }
    
}
