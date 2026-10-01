<?php

namespace Database\Seeders;

use App\Models\AccountCategory;
use Illuminate\Database\Seeder;

class AccountCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Assets',
                'type' => 'asset',
            ],
            [
                'name' => 'Liabilities',
                'type' => 'liability',
            ],
            [
                'name' => 'Equity',
                'type' => 'equity',
            ],
            [
                'name' => 'Income',
                'type' => 'income',
            ],
            [
                'name' => 'Expenses',
                'type' => 'expense',
            ],
        ];

        foreach ($categories as $category) {
            AccountCategory::firstOrCreate(
                ['type' => $category['type']],
                ['name' => $category['name']]
            );
        }
    }
}