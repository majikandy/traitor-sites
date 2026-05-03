<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class PriceObservation extends Model
{
    protected $fillable = [
        'ingredient_id',
        'retailer_id',
        'price_pence',
        'observed_on',
        'source',
        'raw_payload',
    ];

    protected function casts(): array
    {
        return [
            'observed_on' => 'date',
            'raw_payload' => 'array',
        ];
    }

    public function ingredient(): BelongsTo
    {
        return $this->belongsTo(Ingredient::class);
    }

    public function retailer(): BelongsTo
    {
        return $this->belongsTo(Retailer::class);
    }

    protected function priceInPounds(): Attribute
    {
        return Attribute::make(
            get: fn (): float => $this->price_pence / 100,
        );
    }

    protected function priceFormatted(): Attribute
    {
        return Attribute::make(
            get: fn (): string => '£' . number_format($this->price_pence / 100, 2),
        );
    }
}
