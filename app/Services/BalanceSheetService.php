<?php

namespace App\Services;

use App\Models\Account;
use App\Models\JournalEntryLine;
use Illuminate\Support\Collection;

class BalanceSheetService
{
    public function generate(string $asOfDate): array
    {
        $lines = JournalEntryLine::query()
            ->whereHas('journalEntry', function ($query) use ($asOfDate) {
                $query->whereDate('entry_date', '<=', $asOfDate);
            })
            ->get();

        $accounts = Account::with('category')->get();

        $accountBalances = $this->calculateAccountBalances($accounts, $lines);

        $assets = $this->getAccountsByType($accountBalances, 'asset');
        $liabilities = $this->getAccountsByType($accountBalances, 'liability');
        $equity = $this->getAccountsByType($accountBalances, 'equity');
        $income = $this->getAccountsByType($accountBalances, 'income');
        $expenses = $this->getAccountsByType($accountBalances, 'expense');

        $currentEarnings = round(
            $income->sum('balance') - $expenses->sum('balance'),
            2
        );

        $totalAssets = round($assets->sum('balance'), 2);
        $totalLiabilities = round($liabilities->sum('balance'), 2);
        $totalEquity = round($equity->sum('balance') + $currentEarnings, 2);

        $totalLiabilitiesAndEquity = round(
            $totalLiabilities + $totalEquity,
            2
        );

        $difference = round(
            $totalAssets - $totalLiabilitiesAndEquity,
            2
        );

        return [
            'assets' => $assets,
            'liabilities' => $liabilities,
            'equity' => $equity,
            'current_earnings' => $currentEarnings,
            'total_assets' => $totalAssets,
            'total_liabilities' => $totalLiabilities,
            'total_equity' => $totalEquity,
            'total_liabilities_and_equity' => $totalLiabilitiesAndEquity,
            'difference' => $difference,
            'is_balanced' => abs($difference) < 0.01,
        ];
    }

    protected function calculateAccountBalances(
        Collection $accounts,
        Collection $lines
    ): Collection {
        $linesByAccount = $lines->groupBy('account_id');

        return $accounts->map(function ($account) use ($linesByAccount) {
            $accountLines = $linesByAccount->get($account->id, collect());

            $debit = $accountLines->sum(
                fn($line) => (float) $line->debit
            );

            $credit = $accountLines->sum(
                fn($line) => (float) $line->credit
            );

            $type = $account->category->type;

            $balance = match ($type) {
                'asset', 'expense' => $debit - $credit,
                'liability', 'equity', 'income' => $credit - $debit,
                default => 0,
            };

            return [
                'account_id' => $account->id,
                'code' => $account->code,
                'name' => $account->name,
                'type' => $type,
                'debit' => round($debit, 2),
                'credit' => round($credit, 2),
                'balance' => round($balance, 2),
            ];
        });
    }

    protected function getAccountsByType(
        Collection $accounts,
        string $type
    ): Collection {
        return $accounts
            ->where('type', $type)
            ->sortBy('code')
            ->values();
    }
}
