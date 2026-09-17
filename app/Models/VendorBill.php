<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
#[Fillable(['purchase_order_id','vendor_id','bill_number','bill_date','due_date','subtotal','subtotal','tax','total','status','notes'])]
class VendorBill extends Model
{
    protected static function booted(){

        static::creating(function($vb){
            $vb->bill_number = static::generateBillNumber();
        });
    }
    public function items(){
        return $this->hasMany(VendorBillItem::class);
    }

    public function vendor(){
        return $this->belongsTo(Vendor::class);
    }
    public function purchaseOrder(){
        return $this->belongsTo(PurchaseOrder::class);
    }
    public function vendorPayment(){
        return $this->hasOne(VendorPayment::class);
    }

    private static function generateBillNumber(){
         do {
                $bill_number = 'PR-' . strtoupper(Str::random(7));
            } while (
                VendorBill::where(
                    'bill_number',
                    $bill_number
                )->exists()
            );


            return $bill_number;
    }
}
