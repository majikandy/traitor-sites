<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Support\Str;

class TaxonomySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'high-protein-shake'  => 'High Protein Shake',
            'powder-protein'      => 'Powder Protein',
            'protein-com'         => 'Protein',
            'vegetable-protein'   => 'Vegetable Protein',
            'whey-protein-drink'  => 'Whey Protein Drink',
            'hemp-protein'        => 'Hemp Protein',
            'high-protein-breakfast' => 'High Protein Breakfast',
            'lean-protein'        => 'Lean Protein',
            'natural-protein'     => 'Natural Protein',
            'protein-diet-plan'   => 'Protein Diet Plan',
            'protein-foods-list'  => 'Protein Foods List',
            'protein-mix'         => 'Protein Mix',
            'protein-shake-diet'  => 'Protein Shake Diet',
            'pure-protein'        => 'Pure Protein',
            'pure-whey-protein'   => 'Pure Whey Protein',
            'the-best-protein'    => 'The Best Protein',
            'ultimate-protein'    => 'Ultimate Protein',
        ];

        foreach ($categories as $slug => $name) {
            Category::firstOrCreate(['slug' => $slug], ['name' => $name]);
        }

        $tags = [
            '100', 'about', 'alimentares', 'bars', 'benefits', 'best', 'body',
            'bodybuilding', 'breakfast', 'build', 'building', 'carb', 'casein',
            'chocolate', 'como', 'creatine', 'delicious', 'diet', 'diets',
            'drink', 'drinks', 'easier', 'easy', 'eating', 'facts', 'fast',
            'fazer', 'fitness', 'food', 'foods', 'free', 'from', 'gain',
            'gold', 'good', 'great', 'guaranteed', 'hair', 'health', 'healthy',
            'help', 'hemp', 'high', 'highprotein', 'homemade', 'ideal',
            'importance', 'important', 'intake', 'isolate', 'juice', 'know',
            'lean', 'liquid', 'list', 'lose', 'loss', 'love', 'mass', 'meal',
            'milk', 'more', 'much', 'muscle', 'natural', 'need', 'nutrition',
            'opportunities', 'optimum', 'organic', 'pancakes', 'part',
            'pattern', 'plan', 'powder', 'powders', 'power', 'pregnant',
            'product', 'products', 'protein', 'proteins', 'pure', 'quando',
            'recipe', 'recipes', 'replacement', 'review', 'right', 'shake',
            'shakes', 'should', 'smoothie', 'source', 'sources', 'sports',
            'standard', 'super', 'suplementos', 'supplement', 'supplements',
            'tips', 'tomar', 'training', 'treino', 'ultimate', 'understand',
            'using', 'vegan', 'vegetarian', 'video', 'website', 'weight',
            'whey', 'whip', 'women', 'workout', 'zone',
        ];

        foreach ($tags as $slug) {
            Tag::firstOrCreate(
                ['slug' => $slug],
                ['name' => Str::title(str_replace('-', ' ', $slug))]
            );
        }
    }
}
