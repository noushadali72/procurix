<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
#[Fillable(['vendor_bill_id','raw_material_id','qty','unit_id','unit_cost','line_total'])]
class VendorBillItem extends Model
{
    public function vendorBill(){
        return $this->belongsTo(VendorBill::class);
    }
    public function rawMaterial(){
        return $this->belongsTo(RawMaterial::class);
    }
    public function unit(){
        return $this->belongsTo(Unit::class);
    }

}
