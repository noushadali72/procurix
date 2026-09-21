<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Image\Transformations\Contain;

class Role extends Model
{

    public function users(){
        return $this->belongsToMany(User::class);
    }
    public function permissions(){
        return $this->belongsToMany(Permission::class,'role_permissions');
    }

}
