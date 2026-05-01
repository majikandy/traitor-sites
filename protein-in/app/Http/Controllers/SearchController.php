<?php

namespace App\Http\Controllers;

use App\Models\Food;
use App\Models\Search;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SearchController extends Controller
{
    // All USDA FoodData Central datasets to query
    private const USDA_SOURCES = [
        'Foundation',
        'SR Legacy',
        'Survey (FNDDS)',
        'Branded',
    ];

    public function index(Request $request)
    {
        if ($request->filled('q')) {
            return redirect()->route('search.show', urlencode($request->q));
        }
        return view('search.index');
    }

    public function show(string $query)
    {
        $normalised = strtolower(trim($query));

        // Backfill from USDA if this query hasn't been fetched before
        $alreadyFetched = DB::table('usda_queries')->where('query', $normalised)->exists();
        if (!$alreadyFetched) {
            $imported = $this->backfillFromUsda($normalised);
            DB::table('usda_queries')->insert([
                'query'          => $normalised,
                'imported_count' => $imported,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }

        $foods = $this->searchFoods($normalised);

        Search::log($normalised, $foods->total());

        return view('search.results', compact('query', 'foods'));
    }

    private function searchFoods(string $query)
    {
        // Build search terms: original words + de-pluralised variants
        $words = array_filter(explode(' ', $query));
        $terms = collect($words)
            ->flatMap(fn($w) => strlen($w) > 3 && str_ends_with($w, 's')
                ? [$w, rtrim($w, 's')]
                : [$w])
            ->unique()
            ->values();

        return Food::where(function ($q) use ($terms) {
            foreach ($terms as $term) {
                $q->orWhere('name', 'like', "%{$term}%");
            }
        })
        ->orderByDesc('protein_per_100g')
        ->paginate(20)
        ->withQueryString();
    }

    private function backfillFromUsda(string $query): int
    {
        $apiKey = config('services.usda.api_key');
        if (!$apiKey) return 0;

        $imported = 0;

        foreach (self::USDA_SOURCES as $source) {
            try {
                $response = Http::timeout(8)->get('https://api.nal.usda.gov/fdc/v1/foods/search', [
                    'api_key'  => $apiKey,
                    'query'    => $query,
                    'dataType' => $source,
                    'pageSize' => 10,
                ]);

                if (!$response->ok()) continue;

                foreach ($response->json('foods', []) as $food) {
                    $nutrients = collect($food['foodNutrients'] ?? []);
                    $protein = $nutrients->firstWhere('nutrientName', 'Protein')['value'] ?? null;

                    if ($protein === null) continue;

                    $name = Str::title(strtolower($food['description']));
                    $slug = $this->uniqueSlug($name);

                    Food::firstOrCreate(['slug' => $slug], [
                        'name'              => $name,
                        'slug'              => $slug,
                        'usda_source'       => $source,
                        'protein_per_100g'  => round($protein, 1),
                        'calories_per_100g' => $this->nutrient($nutrients, 'Energy'),
                        'fat_per_100g'      => $this->nutrient($nutrients, 'Total lipid (fat)'),
                        'carbs_per_100g'    => $this->nutrient($nutrients, 'Carbohydrate, by difference'),
                        'fibre_per_100g'    => $this->nutrient($nutrients, 'Fiber, total dietary'),
                    ]);

                    $imported++;
                }

            } catch (\Exception) {
                continue;
            }
        }

        return $imported;
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

    private function nutrient(\Illuminate\Support\Collection $nutrients, string $name): ?float
    {
        $val = $nutrients->firstWhere('nutrientName', $name)['value'] ?? null;
        return $val !== null ? round($val, 1) : null;
    }
}
