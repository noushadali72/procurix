<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentTermSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paymentTerms = [
            [
                'name'                => 'Immediate / Due on Receipt',
                'due_days'            => 0,
                'discount_days'       => null,
                'discount_percentage' => 0.00,
                'created_at'          => now(),
                'updated_at'          => now(),
            ],
            [
                'name'                => 'Net 7',
                'due_days'            => 7,
                'discount_days'       => null,
                'discount_percentage' => 0.00,
                'created_at'          => now(),
                'updated_at'          => now(),
            ],
            [
                'name'                => 'Net 15',
                'due_days'            => 15,
                'discount_days'       => null,
                'discount_percentage' => 0.00,
                'created_at'          => now(),
                'updated_at'          => now(),
            ],
            [
                'name'                => 'Net 30',
                'due_days'            => 30,
                'discount_days'       => null,
                'discount_percentage' => 0.00,
                'created_at'          => now(),
                'updated_at'          => now(),
            ],
            [
                'name'                => 'Net 60',
                'due_days'            => 60,
                'discount_days'       => null,
                'discount_percentage' => 0.00,
                'created_at'          => now(),
                'updated_at'          => now(),
            ],
            [
                'name'                => '2/10 Net 30', // 2% discount if paid within 10 days, otherwise full amount due in 30 days
                'due_days'            => 30,
                'discount_days'       => 10,
                'discount_percentage' => 2.00,
                'created_at'          => now(),
                'updated_at'          => now(),
            ],
            [
                'name'                => '3/15 Net 45', // 3% discount if paid within 15 days, otherwise full amount due in 45 days
                'due_days'            => 45,
                'discount_days'       => 15,
                'discount_percentage' => 3.00,
                'created_at'          => now(),
                'updated_at'          => now(),
            ],
        ];

        DB::table('payment_terms')->insert($paymentTerms);
    }
}