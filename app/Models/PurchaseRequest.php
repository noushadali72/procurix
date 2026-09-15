<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PurchaseRequest extends Model
{
    protected $fillable = [
        'request_number',
        'status',
        'notes',
        'due_date'
    ];


    public function items()
    {
        return $this->hasMany(PurchaseRequestItem::class);
    }

      public function quotations()
    {
        return $this->hasMany(Quotation::class);
    }
}