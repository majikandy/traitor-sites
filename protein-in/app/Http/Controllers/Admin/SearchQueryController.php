<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Search;
use App\Models\Food;

class SearchQueryController extends Controller
{
    public function index()
    {
        // All unique queries, grouped, with count + whether we have matching foods
        $queries = Search::selectRaw('query, count(*) as total, sum(results_count) as total_results')
            ->groupBy('query')
            ->orderByDesc('total')
            ->paginate(50);

        return view('admin.searches', compact('queries'));
    }
}
