<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Recipe extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'category',
        'time_minutes',
        'difficulty',
        'description',
        'hero_color',
        'emoji_html',
        'ingredients_json',
        'steps_json',
        'tip',
    ];

    protected function casts(): array
    {
        return [
            'ingredients_json' => 'array',
            'steps_json' => 'array',
        ];
    }

    public function scopeByCategory(Builder $query, string $category): Builder
    {
        return $query->where('category', $category);
    }
}
