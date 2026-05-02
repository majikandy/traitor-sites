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
            return redirect()->route('search.show', rawurlencode($request->q));
        }
        return view('search.index');
    }

    public function show(string $query)
    {
        $normalised = strtolower(trim($query));

        // Check if any variant has never been fetched from USDA
        $needsImport = collect($this->usdaQueryVariants($normalised))
            ->some(fn($q) => !DB::table('usda_queries')->where('query', $q)->exists());

        $foods = $this->searchFoods($normalised);

        Search::log($normalised, $foods->total());

        return view('search.results', compact('query', 'foods', 'needsImport'));
    }

    /**
     * Called via AJAX — runs USDA backfill and returns count of new foods.
     */
    public function backfill(string $query)
    {
        $normalised = strtolower(trim($query));
        $imported = 0;

        foreach ($this->usdaQueryVariants($normalised) as $q) {
            if (!DB::table('usda_queries')->where('query', $q)->exists()) {
                $count = $this->usda->importByQuery($q);
                DB::table('usda_queries')->insert([
                    'query'          => $q,
                    'imported_count' => $count,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);
                $imported += $count;
            }
        }

        return response()->json(['imported' => $imported]);
    }

    private function usdaQueryVariants(string $query): array
    {
        $words = array_values(array_filter(explode(' ', $query)));
        $variants = [$query];

        if (count($words) >= 2) {
            $last = array_pop($words);
            $rest = implode(' ', $words);

            $variants[] = "{$last}, {$rest}";
            $variants[] = "{$last} {$rest}";

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
        ->orderByDesc('view_count')
        ->orderByDesc('protein_per_100g')
        ->paginate(20)
        ->withQueryString();
    }
}
