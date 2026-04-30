<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class FoodImportController extends Controller
{
    public function store(Request $request)
    {
        $request->validate(['query' => 'required|string|max:100']);

        Artisan::call('foods:import', [
            'query' => $request->query,
            '--all' => true,
        ]);

        $output = Artisan::output();

        return back()->with('import_result', $output)->with('import_query', $request->query);
    }
}
