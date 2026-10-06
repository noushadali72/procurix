<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Electronics', 'description' => 'Electronic products and components.', 'inventory_account_id' => 1, 'purchase_account_id' => 9, 'sales_account_id' => 7],
            ['name' => 'Furniture', 'description' => 'Furniture and assembled fixtures.', 'inventory_account_id' => 1, 'purchase_account_id' => 9, 'sales_account_id' => 7],
            ['name' => 'Hardware', 'description' => 'General hardware products and components.', 'inventory_account_id' => 1, 'purchase_account_id' => 9, 'sales_account_id' => 7],
            ['name' => 'Electrical', 'description' => 'Electrical products and equipment.', 'inventory_account_id' => 1, 'purchase_account_id' => 9, 'sales_account_id' => 7],
            ['name' => 'Plumbing', 'description' => 'Plumbing products and fittings.', 'inventory_account_id' => 1, 'purchase_account_id' => 9, 'sales_account_id' => 7],
            ['name' => 'Packaging', 'description' => 'Packaging products and materials.', 'inventory_account_id' => 1, 'purchase_account_id' => 9, 'sales_account_id' => 7],
            ['name' => 'Fasteners', 'description' => 'Nuts, bolts, screws and related fasteners.', 'inventory_account_id' => 1, 'purchase_account_id' => 9, 'sales_account_id' => 7],
            ['name' => 'Metal', 'description' => 'Metal products and components.', 'inventory_account_id' => 1, 'purchase_account_id' => 9, 'sales_account_id' => 7],
            ['name' => 'Plastic', 'description' => 'Plastic products and components.', 'inventory_account_id' => 1, 'purchase_account_id' => 9, 'sales_account_id' => 7],
            ['name' => 'Rubber', 'description' => 'Rubber products and components.', 'inventory_account_id' => 1, 'purchase_account_id' => 9, 'sales_account_id' => 7],
            ['name' => 'Chemicals', 'description' => 'Industrial and production chemicals.', 'inventory_account_id' => 1, 'purchase_account_id' => 9, 'sales_account_id' => 7],
            ['name' => 'Paint & Coatings', 'description' => 'Paints, coatings and related products.', 'inventory_account_id' => 1, 'purchase_account_id' => 9, 'sales_account_id' => 7],
            ['name' => 'Tools', 'description' => 'Hand tools and workshop tools.', 'inventory_account_id' => 1, 'purchase_account_id' => 9, 'sales_account_id' => 7],
            ['name' => 'Safety Equipment', 'description' => 'Personal and workplace safety equipment.', 'inventory_account_id' => 1, 'purchase_account_id' => 9, 'sales_account_id' => 7],
            ['name' => 'Office Supplies', 'description' => 'General office and administrative supplies.', 'inventory_account_id' => 1, 'purchase_account_id' => 9, 'sales_account_id' => 7],
            ['name' => 'Cleaning Supplies', 'description' => '
Cleaning products and janitorial supplies.', 'inventory_account_id' => 1, 'purchase_account_id' => 9, 'sales_account_id' => 7],
            ['name' => 'Automotive', 'description' => 'Automotive parts and accessories.', 'inventory_account_id' => 1, 'purchase_account_id' => 9, 'sales_account_id' => 7],
            ['name' => 'Machinery', 'description' => 'Industrial machinery and equipment.', 'inventory_account_id' => 1, 'purchase_account_id' => 9, 'sales_account_id' => 7],
            ['name' => 'Textiles', 'description' => 'Textile products and fabrics.', 'inventory_account_id' => 1, 'purchase_account_id' => 9, 'sales_account_id' => 7],
            ['name' => 'Wood', 'description' => 'Wood products and materials.', 'inventory_account_id' => 1, 'purchase_account_id' => 9, 'sales_account_id' => 7],
            ['name' => 'Consumables', 'description' => 'General production and operational consumables.', 'inventory_account_id' => 1, 'purchase_account_id' => 9, 'sales_account_id' => 7],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['name' => $category['name']],
                [
                    'slug' => Str::slug($category['name']),
                    'description' => $category['description'],
                    'inventory_account_id' => $category['inventory_account_id'],
                    'purchase_account_id'  => $category['purchase_account_id'],
                    'sales_account_id'     => $category['sales_account_id'],
                ]
            );
        }
    }
}
