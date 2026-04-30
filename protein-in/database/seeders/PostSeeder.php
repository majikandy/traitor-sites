<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('seeders/data/posts.json');

        if (!file_exists($path)) {
            $this->command->warn('posts.json not found — run posts:import then posts:export-seed first.');
            return;
        }

        $posts = json_decode(file_get_contents($path), true);

        foreach ($posts as $data) {
            $post = Post::updateOrCreate(['slug' => $data['slug']], [
                'title'        => $data['title'],
                'slug'         => $data['slug'],
                'content'      => $data['content'],
                'excerpt'      => $data['excerpt'],
                'published_at' => $data['published_at'],
                'status'       => $data['status'],
            ]);

            if (!empty($data['categories'])) {
                $ids = collect($data['categories'])->map(fn($s) =>
                    Category::firstOrCreate(['slug' => $s], ['name' => Str::title(str_replace('-', ' ', $s))])->id
                );
                $post->categories()->sync($ids);
            }

            if (!empty($data['tags'])) {
                $ids = collect($data['tags'])->map(fn($s) =>
                    Tag::firstOrCreate(['slug' => $s], ['name' => Str::title(str_replace('-', ' ', $s))])->id
                );
                $post->tags()->sync($ids);
            }
        }

        $this->command->info('Seeded ' . count($posts) . ' posts.');
    }
}
