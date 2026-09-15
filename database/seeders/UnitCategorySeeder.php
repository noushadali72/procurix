<?php

namespace Database\Seeders;

use App\Models\UnitCategory;
use Illuminate\Database\Seeder;

class UnitCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Weight',
            'Length',
            'Volume',
            'Area',
            'Quantity'
        ];

        foreach ($categories as $category) {
            UnitCategory::firstOrCreate([
                'name' => $category,
            ]);
        }
    }
}