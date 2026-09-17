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
            ['name' => 'Electronics', 'description' => 'Electronic products and components.'],
            ['name' => 'Furniture', 'description' => 'Furniture and assembled fixtures.'],
            ['name' => 'Hardware', 'description' => 'General hardware products and components.'],
            ['name' => 'Electrical', 'description' => 'Electrical products and equipment.'],
            ['name' => 'Plumbing', 'description' => 'Plumbing products and fittings.'],
            ['name' => 'Packaging', 'description' => 'Packaging products and materials.'],
            ['name' => 'Fasteners', 'description' => 'Nuts, bolts, screws and related fasteners.'],
            ['name' => 'Metal', 'description' => 'Metal products and components.'],
            ['name' => 'Plastic', 'description' => 'Plastic products and components.'],
            ['name' => 'Rubber', 'description' => 'Rubber products and components.'],
            ['name' => 'Chemicals', 'description' => 'Industrial and production chemicals.'],
            ['name' => 'Paint & Coatings', 'description' => 'Paints, coatings and related products.'],
            ['name' => 'Tools', 'description' => 'Hand tools and workshop tools.'],
            ['name' => 'Safety Equipment', 'description' => 'Personal and workplace safety equipment.'],
            ['name' => 'Office Supplies', 'description' => 'General office and administrative supplies.'],
            ['name' => 'Cleaning Supplies', 'description' => 'Cleaning products and supplies.'],
            ['name' => 'Food Ingredients', 'description' => 'Food ingredients and processing materials.'],
            ['name' => 'Textile', 'description' => 'Textile products and materials.'],
            ['name' => 'Wood', 'description' => 'Wood products and materials.'],
            ['name' => 'Consumables', 'description' => 'General production and operational consumables.'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['name' => $category['name']],
                [
                    'slug' => Str::slug($category['name']),
                    'description' => $category['description'],
                ]
            );
        }
    }
}