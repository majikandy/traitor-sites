<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['name', 'slug'];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function foods()
    {
        return $this->belongsToMany(Food::class, 'food_tag');
    }

    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_tag');
    }
}
