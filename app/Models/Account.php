<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
#[Fillable(['account_category_id', 'name', 'code', 'is_active'])]
class Account extends Model
{
    public function category()
    {
        return $this->belongsTo(AccountCategory::class, 'account_category_id');
    }
    public function journalEntryLines()
    {
        return $this->hasMany(JournalEntryLine::class, 'account_id');
    }
}
