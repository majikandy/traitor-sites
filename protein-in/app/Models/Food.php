<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    protected $table = 'foods';

    protected $fillable = [
        'name', 'slug', 'protein_per_100g', 'calories_per_100g',
        'fat_per_100g', 'carbs_per_100g', 'fibre_per_100g',
        'description', 'serving_size', 'protein_per_serving',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'food_category');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'food_tag');
    }
}
