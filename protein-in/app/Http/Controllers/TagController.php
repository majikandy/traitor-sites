<?php

namespace App\Http\Controllers;

use App\Models\Tag;

class TagController extends Controller
{
    public function show(Tag $tag)
    {
        $foods = $tag->foods()->orderByDesc('protein_per_100g')->paginate(20);
        $posts = $tag->posts()->published()->orderByDesc('published_at')->paginate(10);
        return view('taxonomy.tag', compact('tag', 'foods', 'posts'));
    }
}
