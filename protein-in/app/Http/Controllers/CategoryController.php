<?php

namespace App\Http\Controllers;

use App\Models\Category;

class CategoryController extends Controller
{
    public function show(Category $category)
    {
        $foods = $category->foods()->orderByDesc('protein_per_100g')->paginate(20);
        $posts = $category->posts()->published()->orderByDesc('published_at')->paginate(10);
        return view('taxonomy.category', compact('category', 'foods', 'posts'));
    }
}
