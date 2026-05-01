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

        // Generate USDA-style query variants — USDA names things as "Beans, baked" / "Chicken, breast"
        foreach ($this->usdaQueryVariants($normalised) as $q) {
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

    /**
     * Generate USDA-style query variants.
     * USDA names: "Beans, baked" / "Chicken, breast" / "Yogurt, Greek"
     *
     * "baked beans"    → ["baked beans", "beans, baked", "beans baked"]
     * "chicken breast" → ["chicken breast", "chicken, breast"]
     * "greek yogurt"   → ["greek yogurt", "yogurt, greek", "yogurt greek"]
     */
    private function usdaQueryVariants(string $query): array
    {
        $words = array_values(array_filter(explode(' ', $query)));
        $variants = [$query];

        if (count($words) >= 2) {
            $last = array_pop($words);
            $rest = implode(' ', $words);

            // "beans, baked" — last word becomes main ingredient (most common USDA pattern)
            $variants[] = "{$last}, {$rest}";
            $variants[] = "{$last} {$rest}";

            // "chicken, breast" — first word is already main (also common)
            $first = array_shift($words);
            $remainder = trim("{$rest}" . (count($words) ? ' ' . implode(' ', $words) : ''));
            if ($first !== $last) {
                $variants[] = "{$first}, {$last}" . ($remainder ? " {$remainder}" : '');
            }
        }

        return array_unique($variants);
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
