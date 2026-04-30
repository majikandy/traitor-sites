<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;

class ExportPostsSeed extends Command
{
    protected $signature = 'posts:export-seed';
    protected $description = 'Export all imported posts to database/seeders/data/posts.json';

    public function handle(): int
    {
        $posts = Post::with('categories', 'tags')->get()->map(fn($post) => [
            'title'        => $post->title,
            'slug'         => $post->slug,
            'content'      => $post->content,
            'excerpt'      => $post->excerpt,
            'published_at' => $post->published_at->toDateString(),
            'status'       => $post->status,
            'categories'   => $post->categories->pluck('slug')->all(),
            'tags'         => $post->tags->pluck('slug')->all(),
        ]);

        $path = database_path('seeders/data/posts.json');
        file_put_contents($path, json_encode($posts, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

        $this->info("Exported {$posts->count()} posts to database/seeders/data/posts.json");
        return 0;
    }
}
