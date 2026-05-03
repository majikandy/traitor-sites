<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Ingredient extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'category',
        'unit',
        'emoji',
        'is_in_index',
        'index_weight',
    ];

    protected function casts(): array
    {
        return [
            'is_in_index' => 'boolean',
            'index_weight' => 'float',
        ];
    }

    public function priceObservations(): HasMany
    {
        return $this->hasMany(PriceObservation::class);
    }

    public function scopeLatestPrices(Builder $query): Builder
    {
        return $query->with(['priceObservations' => function ($q) {
            $q->whereIn('id', function ($sub) {
                $sub->selectRaw('MAX(id)')
                    ->from('price_observations')
                    ->whereColumn('ingredient_id', 'ingredients.id')
                    ->groupBy('retailer_id');
            })->with('retailer');
        }]);
    }
}
