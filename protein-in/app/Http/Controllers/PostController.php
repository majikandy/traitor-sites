<?php

namespace App\Http\Controllers;

use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::published()
            ->orderByDesc('published_at')
            ->paginate(20);

        return view('posts.index', compact('posts'));
    }

    public function show(string $year, string $month, string $day, string $slug)
    {
        $post = Post::whereIn('status', ['published', 'stub'])
            ->whereYear('published_at', $year)
            ->whereMonth('published_at', $month)
            ->whereDay('published_at', $day)
            ->where('slug', $slug)
            ->firstOrFail();

        $post->load('categories', 'tags');

        // Related posts — same era, exclude current
        $related = Post::whereIn('status', ['published', 'stub'])
            ->where('slug', '!=', $slug)
            ->inRandomOrder()
            ->limit(6)
            ->get();

        return view('posts.show', compact('post', 'related'));
    }
}
