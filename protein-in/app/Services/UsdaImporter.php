<?php

namespace App\Services;

use App\Models\Food;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class UsdaImporter
{
    public const SOURCES = ['Foundation', 'SR Legacy', 'Survey (FNDDS)', 'Branded'];

    // All nutrient name variants across USDA datasets
    private const NUTRIENT_MAP = [
        'protein_per_100g'       => ['Protein'],
        'calories_per_100g'      => ['Energy'],
        'fat_per_100g'           => ['Total lipid (fat)'],
        'saturated_fat_per_100g' => ['Fatty acids, total saturated'],
        'mono_fat_per_100g'      => ['Fatty acids, total monounsaturated'],
        'poly_fat_per_100g'      => ['Fatty acids, total polyunsaturated'],
        'carbs_per_100g'         => ['Carbohydrate, by difference'],
        'sugar_per_100g'         => ['Total Sugars', 'Sugars, total including NLEA', 'Sugars, Total'],
        'fibre_per_100g'         => ['Fiber, total dietary'],
        'cholesterol_mg'         => ['Cholesterol'],
        'sodium_mg'              => ['Sodium, Na'],
        'calcium_mg'             => ['Calcium, Ca'],
        'iron_mg'                => ['Iron, Fe'],
        'vitamin_c_mg'           => ['Vitamin C, total ascorbic acid'],
        'omega3_g'               => ['PUFA 18:3', 'PUFA 18:3 c'],
        'water_per_100g'         => ['Water'],
    ];

    // salt = sodium (mg) × 2.5 / 1000  →  g per 100g
    private const SODIUM_TO_SALT = 2.5 / 1000;

    private string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.usda.api_key', '');
    }

    public function available(): bool
    {
        return (bool) $this->apiKey;
    }

    /**
     * Search USDA across all sources and import new foods.
     * Returns count of newly imported foods.
     */
    public function importByQuery(string $query): int
    {
        if (!$this->available()) return 0;

        $imported = 0;

        foreach (self::SOURCES as $source) {
            try {
                $response = Http::timeout(10)->get('https://api.nal.usda.gov/fdc/v1/foods/search', [
                    'api_key'  => $this->apiKey,
                    'query'    => $query,
                    'dataType' => $source,
                    'pageSize' => 10,
                ]);

                if (!$response->ok()) continue;

                foreach ($response->json('foods', []) as $raw) {
                    if ($this->importFood($raw, $source)) {
                        $imported++;
                    }
                }
            } catch (\Exception) {
                continue;
            }
        }

        return $imported;
    }

    /**
     * Backfill missing nutritionals for a food using its stored fdc_id.
     * Returns true if any fields were updated.
     */
    public function backfillFood(Food $food): bool
    {
        if (!$this->available() || !$food->usda_fdc_id) return false;

        try {
            $response = Http::timeout(10)->get("https://api.nal.usda.gov/fdc/v1/food/{$food->usda_fdc_id}", [
                'api_key' => $this->apiKey,
            ]);

            if (!$response->ok()) return false;

            $raw = $response->json();
            $nutrients = collect($raw['foodNutrients'] ?? [])
                ->mapWithKeys(fn($n) => [
                    $n['nutrient']['name'] ?? $n['nutrientName'] ?? '' => $n['amount'] ?? $n['value'] ?? null,
                ]);

            $updates = $this->extractNutrients($nutrients);
            if (empty($updates)) return false;

            $food->update($updates);
            return true;

        } catch (\Exception) {
            return false;
        }
    }

    private function importFood(array $raw, string $source): bool
    {
        $nutrients = collect($raw['foodNutrients'] ?? [])
            ->mapWithKeys(fn($n) => [
                $n['nutrientName'] ?? '' => $n['value'] ?? null,
            ]);

        $protein = $this->resolveNutrient($nutrients, self::NUTRIENT_MAP['protein_per_100g']);
        if ($protein === null) return false;

        $name = Str::title(strtolower($raw['description']));
        $slug = $this->uniqueSlug($name);

        $data = array_merge([
            'name'         => $name,
            'slug'         => $slug,
            'usda_fdc_id'  => $raw['fdcId'] ?? null,
            'usda_source'  => $source,
        ], $this->extractNutrients($nutrients));

        $result = Food::firstOrCreate(['slug' => $slug], $data);
        return $result->wasRecentlyCreated;
    }

    private function extractNutrients(\Illuminate\Support\Collection $nutrients): array
    {
        $data = [];

        foreach (self::NUTRIENT_MAP as $field => $names) {
            $val = $this->resolveNutrient($nutrients, $names);
            if ($val !== null) {
                $precision = str_ends_with($field, '_mg') ? 2 : (str_ends_with($field, 'omega3_g') ? 3 : 1);
                $data[$field] = round($val, $precision);
            }
        }

        // Derive salt from sodium (mg → g, × 2.5)
        if (isset($data['sodium_mg'])) {
            $data['salt_per_100g'] = round($data['sodium_mg'] * self::SODIUM_TO_SALT, 2);
        }

        return $data;
    }

    private function resolveNutrient(\Illuminate\Support\Collection $nutrients, array $names): ?float
    {
        foreach ($names as $name) {
            $val = $nutrients->get($name);
            if ($val !== null) return (float) $val;
        }
        return null;
    }

    private function uniqueSlug(string $name): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $n = 1;
        while (Food::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $n++;
        }
        return $slug;
    }
}
