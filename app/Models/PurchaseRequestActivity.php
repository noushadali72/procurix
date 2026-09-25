<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
#[Fillable(['purchase_request_id','user_id','vendor_id','action','description'])]
class PurchaseRequestActivity extends Model
{
    public function purchaseRequest(){
        return $this->belongsTo(PurchaseRequest::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function vendor(){
        return $this->belongsTo(Vendor::class);
    }
    public function reference(){
        return $this->morphTo();
    }
}
