<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\UsdaImporter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FoodImportController extends Controller
{
    public function __construct(private UsdaImporter $usda) {}

    public function store(Request $request)
    {
        $request->validate(['query' => 'required|string|max:100']);

        $query = strtolower(trim($request->query));
        $imported = $this->usda->importByQuery($query);

        // Mark as fetched so search won't re-query USDA
        DB::table('usda_queries')->upsert(
            ['query' => $query, 'imported_count' => $imported, 'created_at' => now(), 'updated_at' => now()],
            ['query'],
            ['imported_count', 'updated_at']
        );

        $output = "Imported {$imported} new foods for \"{$query}\".";
        return back()->with('import_result', $output)->with('import_query', $query);
    }
}
