<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Search extends Model
{
    protected $fillable = ['query', 'results_count'];

    public static function log(string $query, int $resultsCount): void
    {
        static::create(['query' => strtolower(trim($query)), 'results_count' => $resultsCount]);
    }
}
