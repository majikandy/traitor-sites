<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Food;
use App\Services\UsdaImporter;

class NutritionBackfillController extends Controller
{
    // Fields we can backfill, with friendly labels
    public const FIELDS = [
        'protein_per_100g'       => 'Protein (g/100g)',
        'calories_per_100g'      => 'Calories (kcal/100g)',
        'fat_per_100g'           => 'Fat (g/100g)',
        'saturated_fat_per_100g' => 'Saturated fat (g/100g)',
        'mono_fat_per_100g'      => 'Monounsaturated fat (g/100g)',
        'poly_fat_per_100g'      => 'Polyunsaturated fat (g/100g)',
        'carbs_per_100g'         => 'Carbohydrates (g/100g)',
        'sugar_per_100g'         => 'Sugars (g/100g)',
        'fibre_per_100g'         => 'Fibre (g/100g)',
        'cholesterol_mg'         => 'Cholesterol (mg/100g)',
        'sodium_mg'              => 'Sodium (mg/100g)',
        'salt_per_100g'          => 'Salt (g/100g)',
        'calcium_mg'             => 'Calcium (mg/100g)',
        'iron_mg'                => 'Iron (mg/100g)',
        'vitamin_c_mg'           => 'Vitamin C (mg/100g)',
        'omega3_g'               => 'Omega-3 (g/100g)',
        'water_per_100g'         => 'Water (g/100g)',
    ];

    public function __construct(private UsdaImporter $usda) {}

    public function index()
    {
        $total = Food::count();
        $withFdcId = Food::whereNotNull('usda_fdc_id')->count();

        $coverage = [];
        foreach (self::FIELDS as $field => $label) {
            $coverage[] = [
                'field'   => $field,
                'label'   => $label,
                'filled'  => Food::whereNotNull($field)->count(),
                'missing' => Food::whereNull($field)->count(),
                'total'   => $total,
            ];
        }

        return view('admin.nutrition-backfill', compact('total', 'withFdcId', 'coverage'));
    }

    public function run()
    {
        $foods = Food::whereNotNull('usda_fdc_id')->get();
        $updated = 0;
        $failed = 0;

        foreach ($foods as $food) {
            $result = $this->usda->backfillFood($food);
            $result ? $updated++ : $failed++;
            // Brief sleep to stay under USDA rate limits
            usleep(200000); // 200ms → max ~5 req/s, well within limits
        }

        $msg = "Backfill complete: {$updated} foods updated, {$failed} failed/skipped.";
        return back()->with('backfill_result', $msg);
    }
}
