<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Electronics',
                'slug' => 'electronics',
                'description' => 'Electronic products and components.',
            ],
            [
                'name' => 'Metal',
                'slug' => 'metal',
                'description' => 'Metal products and raw materials.',
            ],
            [
                'name' => 'Plastic',
                'slug' => 'plastic',
                'description' => 'Plastic products and raw materials.',
            ],
            [
                'name' => 'Packaging',
                'slug' => 'packaging',
                'description' => 'Packaging materials and products.',
            ],
            [
                'name' => 'Chemicals',
                'slug' => 'chemicals',
                'description' => 'Chemical materials and products.',
            ],
            [
                'name' => 'Wood',
                'slug' => 'wood',
                'description' => 'Wood products and raw materials.',
            ],
            [
                'name' => 'Textile',
                'slug' => 'textile',
                'description' => 'Textile products and materials.',
            ],
            [
                'name' => 'Tools',
                'slug' => 'tools',
                'description' => 'Tools and equipment.',
            ],
            [
                'name' => 'Hardware',
                'slug' => 'hardware',
                'description' => 'Hardware products and components.',
            ],
            [
                'name' => 'Consumables',
                'slug' => 'consumables',
                'description' => 'General consumable materials and products.',
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}