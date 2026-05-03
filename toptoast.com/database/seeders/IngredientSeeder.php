<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use Illuminate\Database\Seeder;

class IngredientSeeder extends Seeder
{
    public function run(): void
    {
        $ingredients = [
            [
                'name' => 'Avocado',
                'slug' => 'avocado',
                'category' => 'ingredient',
                'unit' => 'each',
                'emoji' => '🥑',
                'is_in_index' => true,
                'index_weight' => 0.1429,
            ],
            [
                'name' => 'Sourdough Loaf',
                'slug' => 'sourdough-loaf',
                'category' => 'ingredient',
                'unit' => '400g loaf',
                'emoji' => '🍞',
                'is_in_index' => true,
                'index_weight' => 0.1429,
            ],
            [
                'name' => 'Free-Range Eggs (6)',
                'slug' => 'free-range-eggs',
                'category' => 'ingredient',
                'unit' => 'box of 6',
                'emoji' => '🥚',
                'is_in_index' => true,
                'index_weight' => 0.1429,
            ],
            [
                'name' => 'Tahini',
                'slug' => 'tahini',
                'category' => 'ingredient',
                'unit' => '300g jar',
                'emoji' => '🫙',
                'is_in_index' => true,
                'index_weight' => 0.1429,
            ],
            [
                'name' => 'Fancy Butter',
                'slug' => 'fancy-butter',
                'category' => 'ingredient',
                'unit' => '250g',
                'emoji' => '🧈',
                'is_in_index' => true,
                'index_weight' => 0.1429,
            ],
            [
                'name' => 'Smashed Avo on Toast',
                'slug' => 'smashed-avo-on-toast',
                'category' => 'dish',
                'unit' => 'per dish',
                'emoji' => '🥑',
                'is_in_index' => true,
                'index_weight' => 0.1429,
            ],
            [
                'name' => 'Sourdough Toast (café)',
                'slug' => 'sourdough-toast-cafe',
                'category' => 'dish',
                'unit' => 'per dish',
                'emoji' => '🍞',
                'is_in_index' => true,
                'index_weight' => 0.1429,
            ],
        ];

        foreach ($ingredients as $ingredient) {
            Ingredient::updateOrCreate(['slug' => $ingredient['slug']], $ingredient);
        }
    }
}
