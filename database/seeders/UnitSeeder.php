<?php

namespace Database\Seeders;

use App\Models\Unit;
use App\Models\UnitCategory;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        $units = [
            'Weight' => [
                ['name' => 'Gram', 'short_name' => 'g', 'conversion_factor' => 1, 'is_base' => true],
                ['name' => 'Kilogram', 'short_name' => 'kg', 'conversion_factor' => 1000, 'is_base' => false],
                ['name' => 'Milligram', 'short_name' => 'mg', 'conversion_factor' => 0.001, 'is_base' => false],
            ],

            'Length' => [
                ['name' => 'Meter', 'short_name' => 'm', 'conversion_factor' => 1, 'is_base' => true],
                ['name' => 'Kilometer', 'short_name' => 'km', 'conversion_factor' => 1000, 'is_base' => false],
                ['name' => 'Centimeter', 'short_name' => 'cm', 'conversion_factor' => 0.01, 'is_base' => false],
                ['name' => 'Millimeter', 'short_name' => 'mm', 'conversion_factor' => 0.001, 'is_base' => false],
            ],

            'Volume' => [
                ['name' => 'Liter', 'short_name' => 'L', 'conversion_factor' => 1, 'is_base' => true],
                ['name' => 'Milliliter', 'short_name' => 'ml', 'conversion_factor' => 0.001, 'is_base' => false],
            ],

            'Area' => [
                ['name' => 'Square Meter', 'short_name' => 'm²', 'conversion_factor' => 1, 'is_base' => true],
                ['name' => 'Square Centimeter', 'short_name' => 'cm²', 'conversion_factor' => 0.0001, 'is_base' => false],
                ['name' => 'Square Millimeter', 'short_name' => 'mm²', 'conversion_factor' => 0.000001, 'is_base' => false],
            ],
            'Quantity' => [
                ['name' => 'Piece', 'short_name' => 'pcs', 'conversion_factor' => 1, 'is_base' => true],
            ],
        ];

        foreach ($units as $categoryName => $categoryUnits) {
            $category = UnitCategory::where('name', $categoryName)->firstOrFail();

            foreach ($categoryUnits as $unit) {
                Unit::firstOrCreate(
                    [
                        'name' => $unit['name'],
                        'short_name' => $unit['short_name'],
                    ],
                    [
                        'unit_category_id' => $category->id,
                        'conversion_factor' => $unit['conversion_factor'],
                        'is_base' => $unit['is_base'],
                    ]
                );
            }
        }
    }
}