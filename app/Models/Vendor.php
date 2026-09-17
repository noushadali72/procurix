<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $fillable = [
        'name',
        'company_name',
        'contact_person',
        'email',
        'phone',
        'ntn',
        'address',
        'is_active',
    ];

    public function quotations(){
        return $this->hasMany(Quotation::class);
    }
    public function vendorBills(){
        return $this->hasMany(VendorBill::class);
    } 
    public function vendorPayments(){
        return $this->hasManyThrough(VendorPayment::class, VendorBill::class);
    }
}