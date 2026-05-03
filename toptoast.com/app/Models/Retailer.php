<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Retailer extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'color',
        'type',
        'fetch_strategy',
        'fetch_config',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'fetch_config' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function priceObservations(): HasMany
    {
        return $this->hasMany(PriceObservation::class);
    }
}
