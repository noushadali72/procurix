<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
#[Fillable(['description', 'entry_date', 'reference'])]
class JournalEntry extends Model
{
    protected $casts = [
        'entry_date' => 'date',
    ];
    public function lines()
    {
        return $this->hasMany(JournalEntryLine::class, 'journal_entry_id');
    }
}
