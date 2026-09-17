<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
#[Fillable(['vendor_bill_id','transaction_id','amount','payment_method','payment_date','status','references','notes'])]
class VendorPayment extends Model
{
    public function vendorBill(){
        return $this->belongsTo(VendorBill::class);
    }    
    
}
