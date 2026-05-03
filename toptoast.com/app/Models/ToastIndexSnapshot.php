<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ToastIndexSnapshot extends Model
{
    protected $fillable = [
        'snapshot_date',
        'index_value',
        'basket_cost_pence',
        'cheapest_retailer_id',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'snapshot_date' => 'date',
            'index_value' => 'decimal:4',
            'basket_cost_pence' => 'integer',
        ];
    }

    public function cheapestRetailer(): BelongsTo
    {
        return $this->belongsTo(Retailer::class, 'cheapest_retailer_id');
    }
}
