<?php

namespace App\Http\Controllers;

use App\Models\Food;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class FoodController extends Controller
{
    public function index()
    {
        $popular = Food::where('view_count', '>', 0)
            ->orderByDesc('view_count')
            ->limit(12)
            ->get();

        $recentIds = session('recently_viewed', []);
        $recentlyViewed = $recentIds
            ? Food::whereIn('id', $recentIds)->get()->sortBy(fn($f) => array_search($f->id, $recentIds))->values()
            : collect();

        $foods = Food::orderByDesc('protein_per_100g')->paginate(20);

        return view('foods.index', compact('foods', 'popular', 'recentlyViewed'));
    }

    public function browse()
    {
        $foods = Food::orderBy('name')->paginate(50);
        return view('foods.browse', compact('foods'));
    }

    public function show(Food $food)
    {
        // Increment view count
        DB::table('foods')->where('id', $food->id)->increment('view_count');

        // Track recently viewed in session (most recent first, max 8)
        $recent = session('recently_viewed', []);
        $recent = array_filter($recent, fn($id) => $id !== $food->id);
        array_unshift($recent, $food->id);
        session(['recently_viewed' => array_slice($recent, 0, 8)]);

        $food->load('categories', 'tags');
        return view('foods.show', compact('food'));
    }

    /**
     * Called async from the food page — fetches image from Open Food Facts and caches it.
     */
    public function fetchImage(Food $food)
    {
        // Already resolved (found or confirmed not found)
        if ($food->image_url !== null) {
            return response()->json(['image_url' => $food->image_url ?: null]);
        }

        $image = $this->fetchOpenFoodFactsImage($food->name);
        DB::table('foods')->where('id', $food->id)->update(['image_url' => $image ?? '']);

        return response()->json(['image_url' => $image]);
    }

    private function fetchOpenFoodFactsImage(string $foodName): ?string
    {
        try {
            $response = Http::timeout(5)
                ->withHeaders(['User-Agent' => 'Protein-In/1.0 (hello@protein-in.com)'])
                ->get('https://world.openfoodfacts.org/cgi/search.pl', [
                    'search_terms' => $foodName,
                    'json'         => 1,
                    'page_size'    => 5,
                    'fields'       => 'product_name,image_thumb_url',
                ]);

            if (!$response->ok()) return null;

            foreach ($response->json('products', []) as $product) {
                $url = $product['image_thumb_url'] ?? null;
                if ($url && str_starts_with($url, 'https://')) {
                    return $url;
                }
            }
        } catch (\Exception) {}

        return null;
    }
}
