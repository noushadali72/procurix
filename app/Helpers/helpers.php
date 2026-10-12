<?php

use App\Models\Activity;
use App\Models\VendorBillActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

if (!function_exists('recordActivity')) {
 function recordActivity(string $action, Model $subject, ?string $description=null, ?Model $reference=null){
    
        return DB::transaction(function() use($action, $description, $subject, $reference) {
            $activity = Activity::create([
                'action'=>$action,
                'description'=>$description,
                'user_id'=>Auth::id(),
            ]);
            
            $activity->subject()->associate($subject);
            $activity->reference()->associate($reference);
            $activity->saveQuietly();

            return $activity;
                    
        });

        
    }
}