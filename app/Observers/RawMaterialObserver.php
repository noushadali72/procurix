<?php

namespace App\Observers;

use App\Models\PurchaseRequest;
use App\Models\RawMaterial;

class RawMaterialObserver
{
    public function updated(RawMaterial $rawMaterial){
        if($rawMaterial->wasChanged('stock') && $rawMaterial->stock <=$rawMaterial->minimum_stock){

            if(!$rawMaterial->hasPurchaseRequest()){

                $pr = PurchaseRequest::create([
                    'status'=>'draft',
                    'stage'=>'request',
            ]);

            $pr->items()->create([
                'raw_material_id'=>$rawMaterial->id,
                'qty'=>$rawMaterial->minimum_stock,
                'unit_id'=>$rawMaterial->unit_id,
                'unit_cost'=>$rawMaterial->cost_price,
                'total'=>$rawMaterial->cost_price*$rawMaterial->minimum_stock
                ]);
        
            }



        }
    }
}
