<?php
namespace App\Services;

use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestActivity;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Model;

class PurchaseRequestActivityService{
    
    public function log(PurchaseRequest $pr, User $user, Vendor $vendor, string $action, string $description, ?Model $reference=null){
        $activity = PurchaseRequestActivity::create([
            'purchase_request_id'=>$pr->id,
            'user_id'=>$user->id,
            'vendor_id'=>$vendor->id,
            'action'=>$action,
            'description'=>$description
        ]);

        if($reference){
            $activity->reference()->associate($reference);
            $activity->save();
        }
    }
}

?>