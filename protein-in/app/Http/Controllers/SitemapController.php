<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Food;
use App\Models\Post;
use App\Models\Tag;

class SitemapController extends Controller
{
    public function index()
    {
        $foods      = Food::orderBy('updated_at', 'desc')->get(['slug', 'updated_at']);
        $posts      = Post::published()->orderBy('published_at', 'desc')->get(['slug', 'published_at']);
        $categories = Category::all(['slug', 'updated_at']);
        $tags       = Tag::all(['slug', 'updated_at']);

        return response()
            ->view('sitemap', compact('foods', 'posts', 'categories', 'tags'))
            ->header('Content-Type', 'application/xml');
    }
}
