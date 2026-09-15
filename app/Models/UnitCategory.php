<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnitCategory extends Model
{
    public function units()
    {
        return $this->hasMany(Unit::class);
    }
}
