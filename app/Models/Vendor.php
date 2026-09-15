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
        'address',
        'is_active',
    ];
}