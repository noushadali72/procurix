<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\AccountCategory;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    public function run(): void
    {
        $categories = AccountCategory::pluck('id', 'type');

        $accounts = [
            [
                'category' => 'asset',
                'name' => 'Inventory',
                'code' => '1100',
            ],
            [
                'category' => 'asset',
                'name' => 'Bank',
                'code' => '1200',
            ],
            [
                'category' => 'asset',
                'name' => 'Cash',
                'code' => '1300',
            ],
            [
                'category' => 'liability',
                'name' => 'Accounts Payable',
                'code' => '2100',
            ],
            [
                'category' => 'equity',
                'name' => 'Owner Equity',
                'code' => '3100',
            ],
            [
                'category' => 'income',
                'name' => 'Sales Revenue',
                'code' => '4100',
            ],
            [
                'category' => 'expense',
                'name' => 'Cost of Goods Sold',
                'code' => '5100',
            ],
            [
                'category' => 'expense',
                'name' => 'Purchases',
                'code' => '5200',
            ],
        ];

        foreach ($accounts as $account) {
            Account::firstOrCreate(
                ['code' => $account['code']],
                [
                    'account_category_id' => $categories[$account['category']],
                    'name' => $account['name'],
                    'is_active' => true,
                ]
            );
        }
    }
}