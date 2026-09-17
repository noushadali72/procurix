<?php

namespace Database\Seeders;

use App\Models\UnitCategory;
use Illuminate\Database\Seeder;

class UnitCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Quantity',
            'Weight',
            'Length',
            'Volume',
            'Area',
        ];

        foreach ($categories as $name) {
            UnitCategory::firstOrCreate([
                'name' => $name,
            ]);
        }
    }
}