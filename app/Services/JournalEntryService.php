<?php

namespace App\Services;

use App\Models\JournalEntry;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class JournalEntryService
{
    public function post(array $headerData, array $lines): JournalEntry
    {

        $this->validate($lines);

        return DB::transaction(function () use (&$headerData, &$lines) {
            $entry = JournalEntry::create([
                'entry_date' => $headerData['entry_date'] ?? now()->toDateString(),
                'description' => $headerData['description'] ?? "",
            ]);
            if(isset($headerData['reference'])){
                $entry->reference()->associate($headerData['reference']);
                $entry->saveQuietly();
            }
            $entryLines = collect($lines)->map(function ($line) {
                return [

                    'account_id' => $line['account_id'],
                    'debit' => round((float)($line['debit'] ?? 0), 2),
                    'credit' => round((float)($line['credit'] ?? 0), 2),
                    'description' => $line['description'] ?? null
                ];
            })->all();

            $entry->lines()->createMany($entryLines);

            return $entry->load('lines.account');
        });
    }

    public function reverse(JournalEntry $entry, ?string $reason = ""): JournalEntry
    {

        return DB::transaction(function () use (&$entry, &$reason) {

            $entry->loadMissing('lines.account');

            $newLines = $entry->lines->map(function ($line) {
                return [
                    'account_id' => $line->account_id,
                    'debit' => $line->credit,
                    'credit' => $line->debit,
                ];
            })->toArray();

            $newEntry =  JournalEntry::create([
                'description' => 'Journal entry reversed. Reason: ' . ($reason ?: 'not provided'),
                'entry_date' => now()->toDateString(),
                'reference' => $entry,
            ]);

         

            $newEntry->lines()->createMany($newLines);
            $newEntry->load('lines.account');

            return $newEntry;
        });
    }

    public function validate(array $lines = []): void
    {

        if (empty($lines)) {
            throw new RuntimeException("Journal entry must contain at least one line.");
        }

        if (!$this->isBalanced($lines)) {
            $totals = $this->calculateTotals($lines);
            throw new RuntimeException("Unbalanced Entry: Total Debit({$totals['total_debit']}) not Equals to Total Credit({$totals['total_credit']})");
        }
    }

    public function isBalanced(array $lines): bool
    {

        $totals = $this->calculateTotals($lines);
        return abs($totals['total_debit'] - $totals['total_credit']) < 0.0001;
    }

    public function calculateTotals(array $lines): array
    {
        $total_credit = 0;
        $total_debit = 0;


        foreach ($lines as $line) {
            $total_credit += (float)($line['credit'] ?? 0);
            $total_debit += (float)($line['debit'] ?? 0);
        }
        return [
            'total_debit' => $total_debit,
            'total_credit' => $total_credit
        ];
    }
}
