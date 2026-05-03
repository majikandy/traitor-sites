<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\PriceObservation;
use App\Models\Retailer;
use App\Models\ToastIndexSnapshot;
use Illuminate\Support\Carbon;

class PriceIndexController extends Controller
{
    public function index()
    {
        $latestSnapshot = ToastIndexSnapshot::latest('snapshot_date')->first();

        $ingredients = Ingredient::latestPrices()->orderBy('name')->get();

        $retailers = Retailer::orderBy('name')->get();

        $recentSnapshots = ToastIndexSnapshot::latest('snapshot_date')->take(4)->get();

        $biggestMovers = $this->getBiggestMovers();

        return view('price-index.index', [
            'latestSnapshot' => $latestSnapshot,
            'ingredients' => $ingredients,
            'retailers' => $retailers,
            'recentSnapshots' => $recentSnapshots,
            'biggestMovers' => $biggestMovers,
        ]);
    }

    public function ingredient(string $slug)
    {
        $ingredient = Ingredient::where('slug', $slug)->firstOrFail();

        $since = Carbon::now()->subWeeks(52);

        $observations = PriceObservation::where('ingredient_id', $ingredient->id)
            ->where('observed_on', '>=', $since)
            ->with('retailer')
            ->orderBy('observed_on')
            ->get();

        $byRetailer = $observations->groupBy('retailer_id');

        $prices = $observations->pluck('price_pence');
        $minPrice = $prices->min();
        $maxPrice = $prices->max();

        $cheapestRetailer = null;
        $mostExpensiveRetailer = null;

        if ($observations->isNotEmpty()) {
            $latestByRetailer = $observations->groupBy('retailer_id')->map(fn ($obs) => $obs->sortByDesc('observed_on')->first());

            $cheapestObs = $latestByRetailer->sortBy('price_pence')->first();
            $mostExpensiveObs = $latestByRetailer->sortByDesc('price_pence')->first();

            $cheapestRetailer = $cheapestObs?->retailer;
            $mostExpensiveRetailer = $mostExpensiveObs?->retailer;
        }

        return view('price-index.ingredient', [
            'ingredient' => $ingredient,
            'byRetailer' => $byRetailer,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
            'cheapestRetailer' => $cheapestRetailer,
            'mostExpensiveRetailer' => $mostExpensiveRetailer,
        ]);
    }

    public function chartData(string $slug)
    {
        $ingredient = Ingredient::where('slug', $slug)->firstOrFail();

        $since = Carbon::now()->subWeeks(52);

        $observations = PriceObservation::where('ingredient_id', $ingredient->id)
            ->where('observed_on', '>=', $since)
            ->with('retailer')
            ->orderBy('observed_on')
            ->get();

        $allDates = $observations->pluck('observed_on')
            ->map(fn ($d) => $d->toDateString())
            ->unique()
            ->sort()
            ->values();

        $byRetailer = $observations->groupBy('retailer_id');

        $colors = ['#e85d26', '#f5c518', '#2d7a2d', '#c0392b', '#8b4513', '#1a1a2e', '#5c3d2e'];
        $colorIndex = 0;

        $datasets = [];

        foreach ($byRetailer as $retailerId => $retailerObs) {
            $retailer = $retailerObs->first()->retailer;
            $color = $retailer->color ?? $colors[$colorIndex % count($colors)];
            $colorIndex++;

            $priceByDate = $retailerObs->keyBy(fn ($o) => $o->observed_on->toDateString());

            $data = $allDates->map(fn ($date) => isset($priceByDate[$date]) ? $priceByDate[$date]->price_pence : null)->values()->toArray();

            $datasets[] = [
                'label' => $retailer->name,
                'data' => $data,
                'borderColor' => $color,
                'backgroundColor' => $color . '22',
                'tension' => 0.3,
                'spanGaps' => true,
            ];
        }

        return response()->json([
            'labels' => $allDates->toArray(),
            'datasets' => $datasets,
        ]);
    }

    private function getBiggestMovers(): array
    {
        $sevenDaysAgo = Carbon::now()->subDays(7)->toDateString();

        $hasOldData = PriceObservation::where('observed_on', '<=', $sevenDaysAgo)->exists();

        if (!$hasOldData) {
            return [];
        }

        $ingredients = Ingredient::all();
        $movers = [];

        foreach ($ingredients as $ingredient) {
            $latest = PriceObservation::where('ingredient_id', $ingredient->id)
                ->whereIn('id', function ($sub) use ($ingredient) {
                    $sub->selectRaw('MAX(id)')
                        ->from('price_observations')
                        ->where('ingredient_id', $ingredient->id)
                        ->groupBy('retailer_id');
                })
                ->avg('price_pence');

            $old = PriceObservation::where('ingredient_id', $ingredient->id)
                ->where('observed_on', '<=', $sevenDaysAgo)
                ->whereIn('id', function ($sub) use ($ingredient, $sevenDaysAgo) {
                    $sub->selectRaw('MAX(id)')
                        ->from('price_observations')
                        ->where('ingredient_id', $ingredient->id)
                        ->where('observed_on', '<=', $sevenDaysAgo)
                        ->groupBy('retailer_id');
                })
                ->avg('price_pence');

            if ($latest === null || $old === null || $old == 0) {
                continue;
            }

            $changePence = round($latest - $old);
            $changePct = round((($latest - $old) / $old) * 100, 1);

            $movers[] = [
                'ingredient' => $ingredient,
                'change_pence' => $changePence,
                'change_pct' => $changePct,
                'latest_pence' => round($latest),
                'old_pence' => round($old),
            ];
        }

        usort($movers, fn ($a, $b) => abs($b['change_pct']) <=> abs($a['change_pct']));

        return array_slice($movers, 0, 3);
    }
}
