<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
#[Fillable(['purchase_request_id','purchase_order_id','selection_reason'])]
class RfqComparison extends Model
{
    public function purchaseOrder(){
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function purchaseRequest(){
        return $this->belongsTo(PurchaseRequest::class);
    }

    public function items(){
        return $this->hasMany(RfqComparisonItem::class);
    }
}
