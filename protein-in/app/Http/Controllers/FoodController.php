<?php

namespace App\Http\Controllers;

use App\Models\Food;
use Illuminate\Support\Facades\DB;

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
}
