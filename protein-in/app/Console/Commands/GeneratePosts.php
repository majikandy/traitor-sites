<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class GeneratePosts extends Command
{
    protected $signature = 'posts:generate {--limit=10 : Number of stubs to generate} {--all : Generate for all stubs}';
    protected $description = 'Generate article content for stub posts using Claude';

    public function handle(): int
    {
        $apiKey = config('services.anthropic.key');
        if (!$apiKey) {
            $this->error('ANTHROPIC_API_KEY not set.');
            return 1;
        }

        $query = Post::where('status', 'stub');
        $limit = $this->option('all') ? PHP_INT_MAX : (int) $this->option('limit');
        $stubs = $query->limit($limit)->get();

        if ($stubs->isEmpty()) {
            $this->info('No stub posts to generate.');
            return 0;
        }

        $this->info("Generating content for {$stubs->count()} posts…");

        foreach ($stubs as $post) {
            $this->line("  Generating: {$post->title}");

            $content = $this->generate($post->title, $apiKey);

            if (!$content) {
                $this->warn("  Failed — skipping {$post->title}");
                continue;
            }

            $post->update([
                'content'  => $content,
                'excerpt'  => Str::limit(strip_tags($content), 200),
                'status'   => 'published',
            ]);

            $this->info("  Done: {$post->title}");
            usleep(300000); // stay within rate limits
        }

        $this->info('Generation complete.');
        return 0;
    }

    private function generate(string $title, string $apiKey): ?string
    {
        $prompt = <<<PROMPT
Write a helpful, informative article titled "{$title}" for Protein-In, a nutrition website focused on protein content in food.

Guidelines:
- 300–500 words
- Conversational but factual tone
- Focus on protein, nutrition, health benefits, and practical tips where relevant
- Return only the article body as clean HTML using <p>, <h2>, <ul>, <li> tags — no <html>, <head>, <body> wrapper
- Do not include the title in the output
PROMPT;

        try {
            $response = Http::withHeaders([
                'x-api-key'         => $apiKey,
                'anthropic-version' => '2023-06-01',
                'content-type'      => 'application/json',
            ])->timeout(30)->post('https://api.anthropic.com/v1/messages', [
                'model'      => 'claude-haiku-4-5-20251001',
                'max_tokens' => 1024,
                'messages'   => [
                    ['role' => 'user', 'content' => $prompt],
                ],
            ]);

            if (!$response->ok()) {
                $this->warn('    API error: ' . $response->status());
                return null;
            }

            return trim($response->json('content.0.text') ?? '');
        } catch (\Exception $e) {
            $this->warn('    Exception: ' . $e->getMessage());
            return null;
        }
    }
}
