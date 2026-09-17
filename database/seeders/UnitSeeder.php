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
            'Quantity' => [
                ['name' => 'Piece', 'short_name' => 'pcs', 'is_base' => true, 'conversion_factor' => 1],
                ['name' => 'Dozen', 'short_name' => 'doz', 'is_base' => false, 'conversion_factor' => 12],
            ],

            'Weight' => [
                ['name' => 'Kilogram', 'short_name' => 'kg', 'is_base' => true, 'conversion_factor' => 1],
                ['name' => 'Gram', 'short_name' => 'g', 'is_base' => false, 'conversion_factor' => 0.001],
                ['name' => 'Milligram', 'short_name' => 'mg', 'is_base' => false, 'conversion_factor' => 0.0001],
                ['name' => 'Ton', 'short_name' => 't', 'is_base' => false, 'conversion_factor' => 1000],
            ],

            'Length' => [
                ['name' => 'Meter', 'short_name' => 'm', 'is_base' => true, 'conversion_factor' => 1],
                ['name' => 'Centimeter', 'short_name' => 'cm', 'is_base' => false, 'conversion_factor' => 0.01],
                ['name' => 'Millimeter', 'short_name' => 'mm', 'is_base' => false, 'conversion_factor' => 0.001],
                ['name' => 'Kilometer', 'short_name' => 'km', 'is_base' => false, 'conversion_factor' => 1000],
            ],

            'Volume' => [
                ['name' => 'Liter', 'short_name' => 'L', 'is_base' => true, 'conversion_factor' => 1],
                ['name' => 'Milliliter', 'short_name' => 'ml', 'is_base' => false, 'conversion_factor' => 0.001],
            ],

            'Area' => [
                ['name' => 'Square Meter', 'short_name' => 'm²', 'is_base' => true, 'conversion_factor' => 1],
                ['name' => 'Square Centimeter', 'short_name' => 'cm²', 'is_base' => false, 'conversion_factor' => 0.0001],
                ['name' => 'Square Millimeter', 'short_name' => 'mm²', 'is_base' => false, 'conversion_factor' => 0.000001],
            ],
        ];

        foreach ($units as $categoryName => $categoryUnits) {
            $category = UnitCategory::where('name', $categoryName)->firstOrFail();

            foreach ($categoryUnits as $unit) {
                Unit::updateOrCreate(
                    ['short_name' => $unit['short_name']],
                    [
                        'name' => $unit['name'],
                        'unit_category_id' => $category->id,
                        'is_base' => $unit['is_base'],
                        'conversion_factor' => $unit['conversion_factor'],
                    ]
                );
            }
        }
    }
}