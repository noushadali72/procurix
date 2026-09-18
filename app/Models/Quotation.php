<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Override;

class Quotation extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_request_id',
        'vendor_id',
        'quotation_number',
        'status',
        'quotation_date',
        'valid_until',
        'notes',
    ];

    protected $casts = [
        'quotation_date' => 'date',
        'valid_until' => 'date',
    ];

    public function purchaseRequest()
    {
        return $this->belongsTo(PurchaseRequest::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function items()
    {
        return $this->hasMany(QuotationItem::class);
    }
    public function purchaseOrder()
    {
        return $this->hasOne(PurchaseOrder::class);
    }

    #[Override]
    protected static function booted()
    {
        static::creating(function(Quotation $q){
            $q->quotation_number = static::generateQuotationNumber();
        });
    }

    private static function generateQuotationNumber():string{
        do{
            $temp = 'QN-'.strtoupper(Str::random(5));
        }while(Quotation::where('quotation_number',$temp)->exists());
    
        return $temp;
        
    }
}