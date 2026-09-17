<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
#[Fillable(['purchase_order_id','vendor_id','bill_number','bill_date','due_date','subtotal','tax','total','status','notes'])]
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
    public function vendorPayments(){
        return $this->hasMany(VendorPayment::class);
    }

    public function getPaidAmountAttribute(){
        return $this->vendorPayments()->where('status','successful')->sum('amount');
    }
    public function getDueAmountAttribute(){
        $paidAmount = $this->paid_amount;
        return max(($this->total - $paidAmount),0);
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
