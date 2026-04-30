<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ImportPosts extends Command
{
    protected $signature = 'posts:import {--fresh : Re-import posts that already exist}';
    protected $description = 'Import legacy posts from Wayback Machine archive of protein-in.com';

    // All known article URLs from protein-in.com (2010-2011)
    protected array $urls = [
        '/2010/01/01/biology-protein-synthesis-an-overview/',
        '/2010/01/01/maximum-protein-experience-epic-meal-time/',
        '/2010/01/03/muscletech-nitro-tech-hardcore-review/',
        '/2011/06/27/whey-to-go-baby-vegetable-whey-protein-smoothie/',
        '/2011/06/28/know-more-about-protein-shakes/',
        '/2011/06/28/diet-recipe-healthy-quinoa-and-vegetable-protein-salad-with-low-fat-dressing-recipe/',
        '/2011/06/30/stevia-all-natural-whey-protein-shake-with-coconut-oil/',
        '/2011/07/01/barry-april-19th-2009-showing-the-vegetable-protein-diet-effects/',
        '/2011/07/05/excitotoxins-the-taste-that-kills-part-1/',
        '/2011/07/10/natural-hair-product-review-shea-moisture-yucca-baobab-shampoo-and-conditioner/',
        '/2011/07/11/excitotoxins-the-taste-that-kills-part-2/',
        '/2011/07/13/why-do-you-need-whey-protein-concentrate-and-whey-protein-intake/',
        '/2011/07/16/protein-shakes-discovered-to-reduce-blood-pressure/',
        '/2011/07/17/the-glycemic-index-food-chart/',
        '/2011/07/18/shea-moisture-yucca-baobab-mizani-cleansing-cream/',
        '/2011/07/19/know-your-whey-from-creatine-confusion-amongst-supplements-2/',
        '/2011/07/19/suplementos-alimentares-pos-treino-como-fazer-quando-tomar/',
        '/2011/07/19/update-june-7th/',
        '/2011/07/21/high-protein-shake-for-cancer/',
        '/2011/07/21/protein-breakfast-on-the-go-with-whey-protein-drink/',
        '/2011/07/22/fit-tipp-13-kg-abgenommen-ohne-jojo-effekt/',
        '/2011/07/22/how-to-get-ripped-fast-a-comprehensive-instruction-to-getting-ripped-abs/',
        '/2011/07/22/how-to-have-a-healthy-diet-with-a-busy-schedule-importance-of-protein-in-diet/',
        '/2011/07/22/tips-on-how-to-gain-weight-quickly-and-which-supplements-to-use/',
        '/2011/07/22/what-does-muscular-developments-anabolic-doctor-think-about-the-muscleology-nitro-pro/',
        '/2011/07/22/whey-protein-evaluate-a-overview-of-the-top-whey-proteins/',
        '/2011/07/24/5-reasons-real-men-eat-yoghurt-theyre-more-manly-than-you-think/',
        '/2011/07/24/7-foods-to-a-flat-belly/',
        '/2011/07/25/my-diet-ultimate-way-to-gain-muscle-and-lose-fat/',
        '/2011/07/26/blood-test-for-cancer-to-early-diagnosis-display-screen-can-easily-help-save-your-lifetime/',
        '/2011/07/26/liver-cancer-causes-symptoms-and-treatment/',
        '/2011/07/26/my-weightloss-journey-oxyelite-pro-review/',
        '/2011/07/26/weight-loss-nutrition-whats-the-best-muscle-building-supplement/',
        '/2011/07/26/whey-protein-isolate-and-its-benefits/',
        '/2011/07/26/will-inovacure-slim-you-down-in-right-spotwmv/',
        '/2011/07/30/chemo-friendly-high-protein-shake/',
        '/2011/07/31/free-low-carb-diet-forum-created-to-help-individuals-to-lose-weight/',
        '/2011/07/31/raw-chocolate-protein-shake-56/',
        '/2011/08/01/making-zoe-juice/',
        '/2011/08/02/e-bariatricsurgery-com-examines-the-bariatric-surgery-diet/',
        '/2011/08/04/gino-being-sick/',
        '/2011/08/07/amway-urunleri-nutrilite-protein-powder-protein-tozu/',
        '/2011/08/07/beat-the-heat-with-healthy-ice-cold-shake/',
        '/2011/08/07/bodybuilding-tips-muscle-building-nutrition/',
        '/2011/08/08/omegawhey-tv-ad-march-2007/',
        '/2011/08/09/outstanding-weight-management-inclusion-whey-protein-shakes/',
        '/2011/08/09/p90x-week-8-day-4/',
        '/2011/08/09/the-gains-of-whey-protein-supplementation/',
        '/2011/08/10/breakfast-at-tiffanys-um-tonis-weight-loss-surgery-friendly-high-protein-breakfast/',
        '/2011/08/10/hcg-approved-protein-shake/',
        '/2011/08/10/high-fiber-foods-list/',
        '/2011/08/11/how-to-build-muscle-fast-best-way-to-build-muscle/',
        '/2011/08/12/diet-advice-about-carbs-fat-and-protein/',
        '/2011/08/22/sales-of-energy-supplement-products-in-the-united-states-increased-from-2-billion-in-1998-to-5-billion-in-2003-and-is-expected-to-reach-8-billion-by-2008/',
        '/2011/09/01/health-testimony-from-a-gal-age-79-1072010-ultimate-chocolate-shake/',
        '/2011/09/05/excitotoxins-the-taste-that-kills-part-5/',
        '/2011/09/08/benefit-of-high-antioxidant-protein-healthy-chocolate-diet-good-for-weight-loss/',
        '/2011/09/10/muscle-build-muscle-build-tips-muscle-build-guide/',
        '/2011/09/12/decision-making-tool-helps-doctors-detect-dengue-more-accurately-11-jun-2011/',
        '/2011/09/12/ideal-protein-diet-breakfast/',
        '/2011/09/12/protein-drinks/',
        '/2011/09/12/weight-loss-day-66/',
        '/2011/09/12/zone-diet-protein-shake-recipes/',
        '/2011/09/14/ensure/',
        '/2011/09/16/ogo-the-chef-shakin-it-hot-with-what-ya-got-chocolate-pudding-at-the-end-of-the-stop/',
        '/2011/09/19/elaineday1/',
        '/2011/09/22/5-crucial-steps-to-lose-body-fat-and-achieve-permanant-weight-loss/',
        '/2011/09/25/foods-to-help-you-have-healthier-hair/',
        '/2011/09/25/the-glycemic-index-food-chart-are-you-getting-enough-protein/',
        '/2011/09/25/whey-protein-powder-vs-weight-gainer/',
        '/2011/09/26/as-a-business-can-i-make-money-selling-tahitian-noni-juice/',
        '/2011/09/26/excellent-fat-reduction-adhering-to-the-healthy-list-of-protein-foods/',
        '/2011/09/26/high-protein-low-fat-meal-recipes/',
        '/2011/09/26/organic-whey-protein-concentrate-for-more-suitable-nourishment/',
        '/2011/09/26/pure-col/',
        '/2011/09/26/whey-protein-supplements-one-course-may-change-living-experience/',
        '/2011/09/26/whey-protein-worth-it/',
        '/2011/09/27/8-keys-to-rapid-weight-loss/',
        '/2011/09/27/low-carb-high-protein-foods-list/',
        '/2011/09/27/progress-bodybuilding-wmv/',
        '/2011/09/27/raw-egg-breakfast/',
        '/2011/09/27/the-best-whey-protein-powder-of-2011/',
        '/2011/09/28/curing-kidney-disease/',
        '/2011/09/28/high-protein-banana-cake-for-a-better-pump/',
        '/2011/09/28/high-protein-diet-plan-for-vegetarians/',
        '/2011/09/28/how-to-get-a-flat-belly-by-combining-proteins-and-carbs/',
        '/2011/09/28/probis-tutorial/',
        '/2011/09/29/2010-06-18-01-eating-healthy-with-dr-david-carfagno-and-tram-mai-mp4/',
        '/2011/09/29/6-week-update-gastric-bypass/',
        '/2011/09/29/great-tasting-healthy-muscle-building-shake/',
        '/2011/09/29/pure-bodybuilding-the-critical-to-countless-expansion-aspect-ii/',
        '/2011/09/29/universal-animal-pak-sports-nutrition-supplement/',
        '/2011/10/01/pure-protein-at-the-2011-arnold/',
        '/2011/10/02/eggcel-power-packed-pancakes/',
        '/2011/10/02/how-to-make-the-best-protein-shake-ever-build-huge-muscles/',
        '/2011/10/02/muscle-whey/',
        '/2011/10/03/healthy-protein-diet-plan/',
        '/2011/10/03/high-protein-breakfast-pancakes-for-muscle-gain/',
        '/2011/10/03/how-to-make-a-protien-mix-with-milk/',
        '/2011/10/03/natural-protein-shake/',
        '/2011/10/03/turkey-sandwich-for-protein-users-part-1/',
        '/2011/10/05/hemp-nutrition-hemp-protein-and-hemp-energy-and-your-love-life/',
        '/2011/10/09/xocai-healthy-chocolate-scam-getting-thin-with-fat-pockets-expert-third-party-review/',
        '/2011/11/20/best-supplement-for-muscle-creatine-kre-alkalyn/',
    ];

    public function handle(): int
    {
        $total = count($this->urls);
        $imported = 0;
        $skipped = 0;
        $failed = 0;

        foreach ($this->urls as $i => $path) {
            $slug = $this->slugFromPath($path);
            $publishedAt = $this->dateFromPath($path);

            if (!$slug || !$publishedAt) {
                $this->warn("  skip (unparseable): {$path}");
                $skipped++;
                continue;
            }

            if (!$this->option('fresh') && Post::where('slug', $slug)->exists()) {
                $this->line("  <fg=gray>exists:</> {$slug}");
                $skipped++;
                continue;
            }

            $this->line("  [" . ($i + 1) . "/{$total}] Fetching {$path}");

            $archiveUrl = "https://web.archive.org/web/{$publishedAt->format('Ymd')}120000/http://protein-in.com{$path}";

            try {
                $response = Http::timeout(15)->get($archiveUrl);

                if (!$response->ok()) {
                    $this->warn("    404/error — skipping");
                    $failed++;
                    continue;
                }

                $html = $response->body();
                $data = $this->extract($html, $slug, $publishedAt);

                if (!$data['title'] || !$data['content']) {
                    $this->warn("    could not extract content — skipping");
                    $failed++;
                    continue;
                }

                $post = Post::updateOrCreate(['slug' => $slug], [
                    'title'        => $data['title'],
                    'slug'         => $slug,
                    'content'      => $data['content'],
                    'excerpt'      => Str::limit(strip_tags($data['content']), 200),
                    'published_at' => $publishedAt,
                    'status'       => 'published',
                ]);

                // Attach categories and tags found in the page
                if (!empty($data['categories'])) {
                    $categoryIds = collect($data['categories'])->map(fn($s) =>
                        Category::firstOrCreate(['slug' => $s], ['name' => Str::title(str_replace('-', ' ', $s))])->id
                    );
                    $post->categories()->sync($categoryIds);
                }

                if (!empty($data['tags'])) {
                    $tagIds = collect($data['tags'])->map(fn($s) =>
                        Tag::firstOrCreate(['slug' => $s], ['name' => Str::title(str_replace('-', ' ', $s))])->id
                    );
                    $post->tags()->sync($tagIds);
                }

                $this->info("    imported: {$data['title']}");
                $imported++;

                // Be polite to Wayback Machine
                usleep(500000);

            } catch (\Exception $e) {
                $this->warn("    error: " . $e->getMessage());
                $failed++;
            }
        }

        $this->newLine();
        $this->info("Done. Imported: {$imported} | Skipped: {$skipped} | Failed: {$failed}");
        return 0;
    }

    private function slugFromPath(string $path): ?string
    {
        $parts = array_filter(explode('/', trim($path, '/')));
        $slug = end($parts);
        return $slug ? rtrim($slug, '/') : null;
    }

    private function dateFromPath(string $path): ?\Carbon\Carbon
    {
        if (preg_match('#/(\d{4})/(\d{2})/(\d{2})/#', $path, $m)) {
            return \Carbon\Carbon::createFromDate($m[1], $m[2], $m[3]);
        }
        return null;
    }

    private function extract(string $html, string $slug, \Carbon\Carbon $date): array
    {
        // Strip Wayback Machine toolbar
        $html = preg_replace('/<div[^>]+id="wm-ipp[^"]*".*?<\/div>/is', '', $html);

        // Title — try common WordPress selectors
        $title = null;
        foreach ([
            '/<h1[^>]*class="[^"]*entry-title[^"]*"[^>]*>(.*?)<\/h1>/is',
            '/<h1[^>]*class="[^"]*post-title[^"]*"[^>]*>(.*?)<\/h1>/is',
            '/<h1[^>]*>(.*?)<\/h1>/is',
            '/<title>(.*?)(?:\s*[|\-–].*)?<\/title>/is',
        ] as $pattern) {
            if (preg_match($pattern, $html, $m)) {
                $title = trim(strip_tags($m[1]));
                if ($title) break;
            }
        }

        // Content — WordPress entry-content div
        $content = '';
        foreach ([
            '/<div[^>]*class="[^"]*entry-content[^"]*"[^>]*>(.*?)<\/div>\s*<\/div>/is',
            '/<div[^>]*class="[^"]*post-content[^"]*"[^>]*>(.*?)<\/div>\s*<\/div>/is',
            '/<div[^>]*class="[^"]*entry[^"]*"[^>]*>(.*?)<\/div>\s*<\/div>/is',
        ] as $pattern) {
            if (preg_match($pattern, $html, $m)) {
                $content = $this->cleanContent($m[1]);
                if (strlen($content) > 100) break;
            }
        }

        // Categories — from rel="category tag" links
        $categories = [];
        preg_match_all('/<a[^>]+rel="category tag"[^>]*href="[^"]*\/category\/([^"\/]+)\/"[^>]*>/i', $html, $cm);
        if (!empty($cm[1])) $categories = array_unique($cm[1]);

        // Tags — from rel="tag" links
        $tags = [];
        preg_match_all('/<a[^>]+rel="tag"[^>]*href="[^"]*\/tag\/([^"\/]+)\/"[^>]*>/i', $html, $tm);
        if (!empty($tm[1])) $tags = array_unique($tm[1]);

        return compact('title', 'content', 'categories', 'tags');
    }

    private function cleanContent(string $html): string
    {
        // Remove scripts, styles, iframes, Wayback banners
        $html = preg_replace('/<(script|style|iframe|noscript)[^>]*>.*?<\/\1>/is', '', $html);
        // Remove Wayback Machine rewrite attributes
        $html = preg_replace('/\s(src|href)="\/web\/\d+\/(http[^"]+)"/i', ' $1="$2"', $html);
        return trim($html);
    }
}
