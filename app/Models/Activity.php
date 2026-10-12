<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
#[Fillable(['subject','action','description','reference','user_id'])]
class Activity extends Model
{
    public function subject(){
        return $this->morphTo();
    }

    public function reference(){
        return $this->morphTo();
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
