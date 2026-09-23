<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
#[Fillable(['name','due_days','discount_days','discount_percentage'])]
class PaymentTerm extends Model
{
    public function vendors(){
        return $this->hasMany(Vendor::class);
    }
}
