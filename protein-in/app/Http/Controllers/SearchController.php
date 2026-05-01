<?php

namespace App\Http\Controllers;

use App\Models\Food;
use App\Models\Search;
use App\Services\UsdaImporter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function __construct(private UsdaImporter $usda) {}

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

        // Also send reversed word order to USDA (e.g. "baked beans" → "beans, baked")
        $queries = array_unique([$normalised, $this->reverseWords($normalised)]);

        foreach ($queries as $q) {
            if (!DB::table('usda_queries')->where('query', $q)->exists()) {
                $imported = $this->usda->importByQuery($q);
                DB::table('usda_queries')->insert([
                    'query'          => $q,
                    'imported_count' => $imported,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);
            }
        }

        $foods = $this->searchFoods($normalised);

        Search::log($normalised, $foods->total());

        return view('search.results', compact('query', 'foods'));
    }

    /** "baked beans" → "beans baked" (USDA stores "Beans, baked") */
    private function reverseWords(string $query): string
    {
        $words = array_filter(explode(' ', $query));
        return count($words) > 1 ? implode(' ', array_reverse($words)) : $query;
    }

    private function searchFoods(string $query)
    {
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
}
