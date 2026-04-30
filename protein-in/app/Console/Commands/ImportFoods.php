<?php

namespace App\Console\Commands;

use App\Models\Food;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ImportFoods extends Command
{
    protected $signature = 'foods:import {query : Search term e.g. "chicken breast"} {--all : Import all results without prompting}';
    protected $description = 'Search USDA FoodData Central and import foods into the database';

    public function handle(): int
    {
        $query = $this->argument('query');
        $apiKey = config('services.usda.api_key');

        if (!$apiKey) {
            $this->error('USDA_API_KEY not set in .env');
            return 1;
        }

        $this->info("Searching USDA for: {$query}");

        $response = Http::get('https://api.nal.usda.gov/fdc/v1/foods/search', [
            'api_key'   => $apiKey,
            'query'     => $query,
            'dataType'  => 'SR Legacy,Foundation,Survey (FNDDS)',
            'pageSize'  => 25,
        ]);

        if (!$response->ok()) {
            $this->error('USDA API error: ' . $response->status());
            return 1;
        }

        $foods = $response->json('foods', []);

        if (empty($foods)) {
            $this->warn('No results found.');
            return 0;
        }

        $rows = [];
        foreach ($foods as $food) {
            $nutrients = collect($food['foodNutrients'] ?? []);
            $protein = $nutrients->firstWhere('nutrientName', 'Protein')['value'] ?? null;
            if ($protein === null) continue;

            $rows[] = [
                'fdcId'    => $food['fdcId'],
                'name'     => $food['description'],
                'protein'  => $protein,
                'calories' => $nutrients->firstWhere('nutrientName', 'Energy')['value'] ?? null,
                'fat'      => $nutrients->firstWhere('nutrientName', 'Total lipid (fat)')['value'] ?? null,
                'carbs'    => $nutrients->firstWhere('nutrientName', 'Carbohydrate, by difference')['value'] ?? null,
                'fibre'    => $nutrients->firstWhere('nutrientName', 'Fiber, total dietary')['value'] ?? null,
            ];
        }

        if (empty($rows)) {
            $this->warn('No foods with protein data found.');
            return 0;
        }

        if (!$this->option('all')) {
            $this->table(['#', 'Name', 'Protein/100g'], array_map(
                fn($i, $r) => [$i + 1, Str::limit($r['name'], 60), $r['protein'] . 'g'],
                array_keys($rows), $rows
            ));

            $choice = $this->ask('Enter numbers to import (e.g. 1,3,5) or "all"', 'all');
            if ($choice !== 'all') {
                $indices = array_map(fn($n) => (int)trim($n) - 1, explode(',', $choice));
                $rows = array_values(array_filter($rows, fn($i) => in_array($i, $indices), ARRAY_FILTER_USE_KEY));
            }
        }

        $imported = 0;
        foreach ($rows as $row) {
            $slug = Str::slug($row['name']);
            // Ensure unique slug
            $base = $slug;
            $n = 1;
            while (Food::where('slug', $slug)->exists()) {
                $slug = $base . '-' . $n++;
            }

            Food::firstOrCreate(['slug' => $slug], [
                'name'             => $row['name'],
                'slug'             => $slug,
                'protein_per_100g' => $row['protein'],
                'calories_per_100g'=> $row['calories'],
                'fat_per_100g'     => $row['fat'],
                'carbs_per_100g'   => $row['carbs'],
                'fibre_per_100g'   => $row['fibre'],
            ]);
            $imported++;
        }

        $this->info("Imported {$imported} food(s).");
        return 0;
    }
}
