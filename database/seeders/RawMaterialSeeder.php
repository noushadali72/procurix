<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\RawMaterial;
use App\Models\Unit;
use Illuminate\Database\Seeder;

class RawMaterialSeeder extends Seeder
{
    public function run(): void
    {
        $materials = [
            ['Steel Sheet', 'Metal', 'kg', 520, 250, 50],
            ['Aluminium Sheet', 'Metal', 'kg', 780, 180, 40],
            ['Copper Wire', 'Electrical', 'kg', 2400, 90, 20],
            ['PVC Granules', 'Plastic', 'kg', 430, 300, 60],
            ['ABS Plastic Granules', 'Plastic', 'kg', 620, 220, 50],
            ['Natural Rubber', 'Rubber', 'kg', 850, 150, 30],
            ['Silicone Rubber', 'Rubber', 'kg', 1250, 100, 25],
            ['Wood Board', 'Wood', 'm', 950, 120, 25],
            ['Pine Wood', 'Wood', 'm', 720, 180, 40],
            ['Cotton Fabric', 'Textile', 'm', 580, 300, 60],
            ['Nylon Fabric', 'Textile', 'm', 760, 180, 40],
            ['Cardboard Sheet', 'Packaging', 'pcs', 45, 500, 100],
            ['Corrugated Box Material', 'Packaging', 'pcs', 65, 400, 80],
            ['Packaging Tape', 'Packaging', 'm', 18, 600, 120],
            ['Epoxy Resin', 'Chemicals', 'kg', 1450, 80, 20],
            ['Industrial Adhesive', 'Chemicals', 'kg', 980, 120, 25],
            ['White Paint', 'Paint & Coatings', 'L', 650, 100, 25],
            ['Black Paint', 'Paint & Coatings', 'L', 680, 80, 20],
            ['Primer', 'Paint & Coatings', 'L', 540, 100, 25],
            ['Lubricating Oil', 'Consumables', 'L', 720, 90, 20],
            ['Cleaning Chemical', 'Cleaning Supplies', 'L', 390, 150, 30],
            ['Glass Cleaner', 'Cleaning Supplies', 'L', 320, 100, 20],
            ['Foam Sheet', 'Packaging', 'm', 280, 250, 50],
            ['Steel Bolt', 'Fasteners', 'pcs', 8, 1500, 300],
            ['Steel Nut', 'Fasteners', 'pcs', 5, 1800, 350],
            ['Steel Screw', 'Fasteners', 'pcs', 6, 2000, 400],
            ['LED Chip', 'Electronics', 'pcs', 35, 1000, 200],
            ['Circuit Board', 'Electronics', 'pcs', 450, 300, 60],
            ['Electrical Insulation Tape', 'Electrical', 'm', 12, 500, 100],
            ['Safety Foam', 'Safety Equipment', 'kg', 900, 70, 15],
        ];

        foreach ($materials as $index => $material) {
            [$name, $categoryName, $unitShortName, $cost, $stock, $minimumStock] = $material;

            RawMaterial::updateOrCreate(
                [
                    'sku' => 'RM-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                ],
                [
                    'name' => $name,
                    'cost_price' => $cost,
                    'stock' => $stock,
                    'unit_id' => Unit::where('short_name', $unitShortName)->value('id'),
                    'category_id' => Category::where('name', $categoryName)->value('id'),
                    'minimum_stock' => $minimumStock,
                    'description' => $name . ' used as an inventory raw material.',
                ]
            );
        }
    }
}