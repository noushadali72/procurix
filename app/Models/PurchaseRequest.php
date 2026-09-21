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
        'due_date',
        'delivery_address'
    ];

   protected static function booted(){
    static::creating(function($pr){
        $pr->request_number = static::generateRequestNumber();
    });
   }


    public function items()
        {
            return $this->hasMany(PurchaseRequestItem::class);
        }

    public function quotations()
        {
            return $this->hasMany(Quotation::class);
        }

     private static function generateRequestNumber(): string
        {
            do {

                $requestNumber =
                    'PR-' . strtoupper(Str::random(7));
            } while (
                PurchaseRequest::where(
                    'request_number',
                    $requestNumber
                )->exists()
            );


            return $requestNumber;
        }
}