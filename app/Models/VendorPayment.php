<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
#[Fillable(['vendor_bill_id','transaction_id','amount','payment_method','payment_date','status','payment_proof','references','notes'])]
class VendorPayment extends Model
{
    
    protected $casts = [
        'payment_date'=>'date',
        'amount'=>'decimal:2'
    ];
    public function vendorBill(){
        return $this->belongsTo(VendorBill::class);
    }    
    
    
}
