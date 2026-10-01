<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
#[Fillable(['name', 'type', 'description'])]
class AccountCategory extends Model
{
    public function accounts()
    {
        return $this->hasMany(Account::class, 'account_category_id');
    }
}
