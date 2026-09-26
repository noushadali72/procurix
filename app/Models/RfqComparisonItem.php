<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
#[Fillable(['rfq_comparison_id','purchase_request_id','raw_material_id','qty','unit_id','unit_cost','line_total'])]
class RfqComparisonItem extends Model
{
    public function rfqComparison(){
        return $this->belongsTo(RfqComparison::class);
    }
    public function unit(){
        return $this->belongsTo(Unit::class);
    }
    public function rawMaterial(){
        return $this->belongsTo(RawMaterial::class);
    }
    public function purchaseRequest(){
        return $this->belongsTo(PurchaseRequest::class);
    }
    
}
