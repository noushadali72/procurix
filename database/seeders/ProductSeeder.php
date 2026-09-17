<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Unit;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['Laptop Computer', 'Electronics', 'pcs', 85000, 105000, 25, 5],
            ['Desktop Computer', 'Electronics', 'pcs', 65000, 82000, 18, 5],
            ['LED Monitor', 'Electronics', 'pcs', 28000, 36000, 30, 5],
            ['Wireless Keyboard', 'Electronics', 'pcs', 4500, 6500, 40, 10],
            ['Wireless Mouse', 'Electronics', 'pcs', 2200, 3500, 55, 10],
            ['Office Chair', 'Furniture', 'pcs', 18000, 25000, 20, 5],
            ['Office Desk', 'Furniture', 'pcs', 30000, 42000, 12, 3],
            ['Filing Cabinet', 'Furniture', 'pcs', 22000, 30000, 8, 2],
            ['Power Extension Board', 'Electrical', 'pcs', 1800, 2800, 35, 8],
            ['Ceiling Fan', 'Electrical', 'pcs', 9500, 13000, 15, 4],
            ['LED Bulb 12W', 'Electrical', 'pcs', 450, 700, 100, 20],
            ['Water Tap', 'Plumbing', 'pcs', 2500, 3800, 25, 5],
            ['PVC Pipe 1m', 'Plumbing', 'pcs', 350, 550, 70, 15],
            ['Steel Bolt Set', 'Fasteners', 'pcs', 120, 250, 150, 30],
            ['Metal Storage Rack', 'Metal', 'pcs', 14000, 19500, 10, 3],
            ['Plastic Storage Box', 'Plastic', 'pcs', 900, 1500, 60, 15],
            ['Rubber Door Seal', 'Rubber', 'm', 180, 300, 80, 20],
            ['Safety Helmet', 'Safety Equipment', 'pcs', 900, 1400, 45, 10],
            ['Safety Gloves', 'Safety Equipment', 'pcs', 250, 450, 120, 30],
            ['Tool Kit', 'Tools', 'pcs', 6500, 9000, 18, 5],
            ['Paint Roller Set', 'Paint & Coatings', 'pcs', 750, 1200, 30, 8],
            ['Cleaning Bucket', 'Cleaning Supplies', 'pcs', 500, 850, 40, 10],
            ['Wooden Shelf', 'Wood', 'pcs', 7500, 10500, 15, 4],
            ['Cotton Fabric Roll', 'Textile', 'm', 650, 950, 50, 10],
            ['Packaging Carton', 'Packaging', 'pcs', 180, 300, 200, 50],
            ['Adhesive Tape', 'Packaging', 'pcs', 120, 220, 150, 30],
            ['USB Cable', 'Electronics', 'pcs', 350, 650, 100, 20],
            ['Power Adapter', 'Electronics', 'pcs', 1200, 1900, 50, 10],
            ['Industrial Cleaning Kit', 'Consumables', 'pcs', 1800, 2800, 25, 5],
            ['Office Stationery Pack', 'Office Supplies', 'pcs', 800, 1300, 35, 8],
        ];

        foreach ($products as $index => $product) {
            [$name, $categoryName, $unitShortName, $cost, $sale, $stock, $minimumStock] = $product;

            Product::updateOrCreate(
                [
                    'sku' => 'PRD-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                ],
                [
                    'name' => $name,
                    'unit_id' => Unit::where('short_name', $unitShortName)->value('id'),
                    'category_id' => Category::where('name', $categoryName)->value('id'),
                    'cost_price' => $cost,
                    'sale_price' => $sale,
                    'stock' => $stock,
                    'minimum_stock' => $minimumStock,
                    'description' => $name . ' for inventory and sales.',
                ]
            );
        }
    }
}