<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\JournalEntry;
use Illuminate\Http\Request;

class JournalEntryController extends Controller
{
    public function index(Request $request)
    {
        $entries = JournalEntry::query()
            ->with('reference')
            ->withSum('lines', 'debit')
            ->withSum('lines', 'credit')
            ->when($request->search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('reference_no', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($request->date_from, fn ($query, $date) =>
                $query->whereDate('entry_date', '>=', $date)
            )
            ->when($request->date_to, fn ($query, $date) =>
                $query->whereDate('entry_date', '<=', $date)
            )
            ->when($request->account_id, function ($query, $accountId) {
                $query->whereHas('lines', function ($query) use ($accountId) {
                    $query->where('account_id', $accountId);
                });
            })
            ->latest('entry_date')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        $accounts = Account::orderBy('code')->get();

        return view('journal_entries.index', compact(
            'entries',
            'accounts'
        ));
    }

    public function show(JournalEntry $journalEntry)
    {
        $journalEntry->load([
            'lines.account',
            'reference',
        ]);

        $totalDebit = $journalEntry->lines->sum('debit');
        $totalCredit = $journalEntry->lines->sum('credit');

        return view('journal_entries.show', compact(
            'journalEntry',
            'totalDebit',
            'totalCredit'
        ));
    }
}