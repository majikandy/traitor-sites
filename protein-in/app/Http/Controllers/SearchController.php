<?php

namespace App\Http\Controllers;

use App\Models\Food;
use App\Models\Search;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        if ($request->filled('q')) {
            return redirect()->route('search.show', urlencode($request->q));
        }
        return view('search.index');
    }

    public function show(string $query)
    {
        $foods = $this->searchFoods($query);

        // Zero results — try to backfill from USDA then re-query
        if ($foods->total() === 0) {
            $imported = $this->backfillFromUsda($query);
            if ($imported > 0) {
                $foods = $this->searchFoods($query);
            }
        }

        Search::log($query, $foods->total());

        return view('search.results', compact('query', 'foods'));
    }

    private function searchFoods(string $query)
    {
        return Food::where('name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->orderByDesc('protein_per_100g')
            ->paginate(20)
            ->withQueryString();
    }

    private function backfillFromUsda(string $query): int
    {
        $apiKey = config('services.usda.api_key');
        if (!$apiKey) return 0;

        try {
            $response = Http::timeout(8)->get('https://api.nal.usda.gov/fdc/v1/foods/search', [
                'api_key'  => $apiKey,
                'query'    => $query,
                'dataType' => 'SR Legacy,Foundation',
                'pageSize' => 10,
            ]);

            if (!$response->ok()) return 0;

            $imported = 0;
            foreach ($response->json('foods', []) as $food) {
                $nutrients = collect($food['foodNutrients'] ?? []);
                $protein = $nutrients->firstWhere('nutrientName', 'Protein')['value'] ?? null;

                if ($protein === null) continue;

                $name = Str::title(strtolower($food['description']));
                $slug = $this->uniqueSlug($name);

                Food::firstOrCreate(['slug' => $slug], [
                    'name'              => $name,
                    'slug'              => $slug,
                    'protein_per_100g'  => round($protein, 1),
                    'calories_per_100g' => $this->nutrient($nutrients, 'Energy'),
                    'fat_per_100g'      => $this->nutrient($nutrients, 'Total lipid (fat)'),
                    'carbs_per_100g'    => $this->nutrient($nutrients, 'Carbohydrate, by difference'),
                    'fibre_per_100g'    => $this->nutrient($nutrients, 'Fiber, total dietary'),
                ]);

                $imported++;
            }

            return $imported;

        } catch (\Exception) {
            return 0;
        }
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
