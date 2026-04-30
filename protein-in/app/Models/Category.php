<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'description'];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function foods()
    {
        return $this->belongsToMany(Food::class, 'food_category');
    }

    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_category');
    }
}
