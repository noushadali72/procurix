<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Services\JournalEntryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OpeningBalanceController extends Controller
{
    public function create()
    {
        $accounts = Account::query()
            ->where('is_active', true)
            ->whereHas('category', function ($query) {
                $query->whereIn('type', [
                    'asset',
                    'liability',
                    'equity',
                ]);
            })
            ->with('category')
            ->orderBy('code')
            ->get();

        return view('opening_balances.create', compact('accounts'));
    }

    public function store(Request $request, JournalEntryService $journalEntryService)
    {
        $validated = $request->validate([
            'entry_date' => ['required', 'date'],
            'lines' => ['required', 'array', 'min:1'],
            'lines.*.account_id' => ['required', 'integer', 'exists:accounts,id'],
            'lines.*.debit' => ['nullable', 'numeric', 'min:0'],
            'lines.*.credit' => ['nullable', 'numeric', 'min:0'],
        ]);

        $lines = [];

        foreach ($validated['lines'] as $line) {
            $debit = round((float) ($line['debit'] ?? 0), 2);
            $credit = round((float) ($line['credit'] ?? 0), 2);

            // Ignore completely empty rows.
            if ($debit == 0 && $credit == 0) {
                continue;
            }

            // One line cannot contain both debit and credit.
            if ($debit > 0 && $credit > 0) {
                throw ValidationException::withMessages([
                    'lines' => 'A line cannot have both debit and credit.',
                ]);
            }

            $lines[] = [
                'account_id' => $line['account_id'],
                'debit' => $debit,
                'credit' => $credit,
                'description' => 'Opening balance',
            ];
        }

        if (empty($lines)) {
            throw ValidationException::withMessages([
                'lines' => 'Enter at least one opening balance.',
            ]);
        }

        /*
         * Opening balances are only allowed for
         * balance sheet accounts.
         */
        $accountIds = collect($lines)
            ->pluck('account_id')
            ->unique();

        $validAccountIds = Account::query()
            ->whereIn('id', $accountIds)
            ->where('is_active', true)
            ->whereHas('category', function ($query) {
                $query->whereIn('type', [
                    'asset',
                    'liability',
                    'equity',
                ]);
            })
            ->pluck('id');

        if ($accountIds->diff($validAccountIds)->isNotEmpty()) {
            throw ValidationException::withMessages([
                'lines' => 'Opening balances can only be posted to active Asset, Liability, or Equity accounts.',
            ]);
        }

        $totals = $journalEntryService->calculateTotals($lines);

        if (abs($totals['total_debit'] - $totals['total_credit']) >= 0.0001) {
            throw ValidationException::withMessages([
                'lines' => 'Opening balances must be balanced. Total debit must equal total credit.',
            ]);
        }

        $entry = DB::transaction(function () use (
            $journalEntryService,
            $validated,
            $lines
        ) {
            return $journalEntryService->post(
                [
                    'entry_date' => $validated['entry_date'],
                    'description' => 'Opening balances',
                ],
                $lines
            );
        });

        return redirect()
            ->route('balance-sheet.index')
            ->with(
                'success',
                "Opening balances posted successfully. {$entry->reference}"
            );
    }
}
