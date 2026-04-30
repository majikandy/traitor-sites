<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Food;
use App\Models\Search;

class DashboardController extends Controller
{
    public function index()
    {
        $foodCount = Food::count();
        $searchCount = Search::count();
        $zeroResultSearches = Search::where('results_count', 0)
            ->selectRaw('query, count(*) as total')
            ->groupBy('query')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('foodCount', 'searchCount', 'zeroResultSearches'));
    }
}
