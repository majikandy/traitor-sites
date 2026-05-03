<?php

namespace App\Services;

use App\Models\Ingredient;
use App\Models\PriceObservation;
use App\Models\ToastIndexSnapshot;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ToastIndexCalculator
{
    public function calculate(): array
    {
        $ingredients = Ingredient::where('is_in_index', true)->get();

        $basketCostPence = 0;
        $retailerTotals = [];

        foreach ($ingredients as $ingredient) {
            $latestObservations = PriceObservation::where('ingredient_id', $ingredient->id)
                ->whereIn('id', function ($sub) use ($ingredient) {
                    $sub->selectRaw('MAX(id)')
                        ->from('price_observations')
                        ->where('ingredient_id', $ingredient->id)
                        ->groupBy('retailer_id');
                })
                ->pluck('price_pence', 'retailer_id');

            if ($latestObservations->isEmpty()) {
                continue;
            }

            $prices = $latestObservations->values()->sort()->values();
            $count = $prices->count();
            $median = $count % 2 === 0
                ? ($prices[$count / 2 - 1] + $prices[$count / 2]) / 2
                : $prices[intdiv($count, 2)];

            $basketCostPence += (int) round($median * $ingredient->index_weight);

            foreach ($latestObservations as $retailerId => $price) {
                $retailerTotals[$retailerId] = ($retailerTotals[$retailerId] ?? 0)
                    + (int) round($price * $ingredient->index_weight);
            }
        }

        $baselinePence = config('toptoast.index_baseline_cost_pence');
        $indexValue = $basketCostPence > 0 ? ($basketCostPence / $baselinePence) * 100 : 0;

        $cheapestRetailerId = null;
        if (!empty($retailerTotals)) {
            $cheapestRetailerId = array_key_first(
                array_filter($retailerTotals, fn ($v) => $v === min($retailerTotals))
            );
        }

        return [
            'index_value' => round($indexValue, 4),
            'basket_cost_pence' => $basketCostPence,
            'cheapest_retailer_id' => $cheapestRetailerId,
        ];
    }

    public function saveSnapshot(): ToastIndexSnapshot
    {
        $result = $this->calculate();

        $snapshot = ToastIndexSnapshot::updateOrCreate(
            ['snapshot_date' => Carbon::today()->toDateString()],
            [
                'index_value' => $result['index_value'],
                'basket_cost_pence' => $result['basket_cost_pence'],
                'cheapest_retailer_id' => $result['cheapest_retailer_id'],
            ]
        );

        return $snapshot;
    }
}
