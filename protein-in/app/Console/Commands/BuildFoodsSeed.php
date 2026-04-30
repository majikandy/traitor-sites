<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class BuildFoodsSeed extends Command
{
    protected $signature = 'foods:build-seed {--target=500 : Target number of foods}';
    protected $description = 'Query USDA FoodData Central across food categories and write database/seeders/data/foods.json';

    protected array $searches = [
        // Poultry
        'chicken breast', 'chicken thigh', 'chicken wing', 'chicken drumstick', 'turkey breast',
        'turkey ground', 'duck breast', 'goose', 'quail', 'chicken liver', 'turkey leg',
        'chicken whole roasted', 'cornish hen',
        // Beef cuts
        'beef sirloin', 'beef ground', 'beef tenderloin', 'beef ribeye', 'beef brisket',
        'beef liver', 'beef chuck', 'veal', 'beef flank steak', 'beef skirt steak',
        'beef round', 'beef short ribs', 'beef tongue', 'beef kidney', 'beef heart',
        'beef oxtail', 'beef mince', 'beef rump',
        // Pork cuts
        'pork tenderloin', 'pork chop', 'pork belly', 'pork shoulder', 'bacon', 'ham',
        'prosciutto', 'pork ribs', 'pork loin', 'pork mince', 'pork sausage',
        'pork liver', 'pork kidney',
        // Lamb cuts
        'lamb chop', 'lamb leg', 'lamb shoulder', 'lamb rack', 'lamb mince',
        'lamb liver', 'lamb kidney', 'lamb shank',
        // Game
        'venison', 'bison', 'elk', 'rabbit', 'wild boar', 'pheasant', 'partridge',
        // Fish
        'salmon', 'tuna', 'cod', 'halibut', 'tilapia', 'sea bass', 'swordfish', 'mahi mahi',
        'trout', 'herring', 'mackerel', 'sardines', 'anchovies', 'snapper', 'haddock', 'pollock',
        'catfish', 'flounder', 'sole', 'monkfish', 'pike', 'perch', 'carp', 'sea bream',
        'turbot', 'plaice', 'whitebait', 'eel', 'kippers',
        // Shellfish
        'shrimp', 'crab', 'lobster', 'scallops', 'mussels', 'oysters', 'clams', 'squid', 'octopus',
        'langoustine', 'whelks', 'cockles', 'crayfish',
        // Dairy
        'greek yogurt', 'cottage cheese', 'ricotta', 'mozzarella', 'cheddar cheese', 'parmesan',
        'gouda', 'brie', 'feta', 'halloumi', 'skyr', 'kefir', 'milk whole', 'milk skim',
        'whey protein powder', 'casein protein powder', 'cream cheese', 'stilton',
        'edam', 'gruyere', 'emmental', 'camembert', 'mascarpone', 'milk 2 percent',
        'double cream', 'sour cream', 'creme fraiche',
        // Eggs
        'whole egg', 'egg white', 'egg yolk',
        // Legumes
        'lentils cooked', 'chickpeas cooked', 'black beans cooked', 'kidney beans cooked',
        'pinto beans cooked', 'navy beans cooked', 'soybeans cooked', 'edamame',
        'split peas cooked', 'mung beans cooked', 'adzuki beans cooked', 'fava beans cooked',
        'tofu firm', 'tofu silken', 'tempeh', 'natto', 'seitan', 'textured vegetable protein',
        'cannellini beans cooked', 'butter beans cooked', 'borlotti beans cooked',
        'black eyed peas cooked',
        // Nuts
        'almonds', 'peanuts', 'cashews', 'pistachios', 'walnuts', 'pecans', 'brazil nuts',
        'macadamia nuts', 'hazelnuts', 'pine nuts', 'chestnuts', 'coconut',
        // Seeds
        'pumpkin seeds', 'sunflower seeds', 'hemp seeds', 'chia seeds', 'flaxseeds',
        'sesame seeds', 'poppy seeds', 'watermelon seeds',
        // Nut & seed butters
        'peanut butter', 'almond butter', 'tahini', 'cashew butter', 'sunflower seed butter',
        // Grains (dry & cooked)
        'quinoa cooked', 'oats rolled', 'amaranth cooked', 'buckwheat cooked', 'teff cooked',
        'barley cooked', 'farro cooked', 'spelt cooked',
        'brown rice cooked', 'wild rice cooked', 'bulgur cooked', 'white rice cooked',
        'couscous cooked', 'polenta cooked', 'millet cooked',
        // Bread & pasta
        'whole wheat bread', 'sourdough bread', 'rye bread', 'white bread',
        'whole wheat pasta cooked', 'white pasta cooked', 'lentil pasta', 'chickpea pasta',
        'rice noodles cooked', 'egg noodles cooked',
        // ALL FRUITS
        'apple', 'banana', 'orange', 'pear', 'grape', 'strawberry', 'blueberry', 'raspberry',
        'blackberry', 'cherry', 'peach', 'plum', 'apricot', 'nectarine', 'mango', 'pineapple',
        'papaya', 'kiwi', 'watermelon', 'cantaloupe', 'honeydew melon', 'lemon', 'lime',
        'grapefruit', 'tangerine', 'clementine', 'pomegranate', 'fig', 'date', 'prune',
        'raisin', 'cranberry', 'gooseberry', 'elderberry', 'mulberry', 'passion fruit',
        'guava', 'lychee', 'jackfruit', 'durian', 'dragon fruit', 'starfruit', 'persimmon',
        'quince', 'medlar', 'coconut meat', 'avocado', 'olive',
        // ALL VEGETABLES
        'potato', 'sweet potato', 'carrot', 'broccoli', 'cauliflower', 'cabbage', 'kale',
        'spinach', 'lettuce', 'rocket', 'watercress', 'chard', 'collard greens', 'bok choy',
        'brussels sprouts', 'asparagus', 'green beans', 'peas', 'corn', 'courgette',
        'zucchini', 'cucumber', 'tomato', 'pepper red', 'pepper green', 'pepper yellow',
        'onion', 'red onion', 'spring onion', 'leek', 'garlic', 'shallot',
        'beetroot', 'turnip', 'parsnip', 'swede', 'celeriac', 'artichoke', 'fennel',
        'celery', 'mushroom button', 'mushroom portobello', 'mushroom shiitake',
        'mushroom oyster', 'aubergine', 'eggplant', 'pumpkin', 'butternut squash',
        'acorn squash', 'spaghetti squash', 'radish', 'daikon', 'kohlrabi', 'okra',
        'sweetcorn', 'mange tout', 'sugar snap peas', 'broad beans', 'runner beans',
        'chicory', 'endive', 'radicchio', 'pak choi', 'tenderstem broccoli',
        'purple sprouting broccoli', 'romanesco', 'savoy cabbage',
        // Herbs (some protein value)
        'parsley', 'basil', 'coriander', 'mint', 'thyme', 'oregano',
        // Root veg & starchy
        'yam', 'cassava', 'taro', 'plantain',
        // Algae & superfoods
        'spirulina dried', 'chlorella', 'nutritional yeast',
        // Protein snacks & deli
        'beef jerky', 'turkey jerky', 'protein bar',
        'salami', 'pepperoni', 'chorizo', 'mortadella', 'bologna', 'pastrami',
        'corned beef', 'liverwurst',
        // Sauces & condiments with protein
        'hummus', 'tzatziki',
    ];

    public function handle(): int
    {
        $target = (int) $this->option('target');
        $apiKey = config('services.usda.api_key');

        if (!$apiKey) {
            $this->error('USDA_API_KEY not set in .env');
            return 1;
        }

        $existing = $this->loadExisting();
        $slugsSeen = collect($existing)->pluck('slug')->flip()->all();
        $foods = $existing;

        $this->info('Starting with ' . count($foods) . ' existing foods. Target: ' . $target);

        foreach ($this->searches as $term) {
            if (count($foods) >= $target) break;

            $this->line("  Fetching: {$term} (" . count($foods) . "/{$target})");

            try {
                $response = Http::timeout(10)->get('https://api.nal.usda.gov/fdc/v1/foods/search', [
                    'api_key'  => $apiKey,
                    'query'    => $term,
                    'dataType' => 'SR Legacy,Foundation',
                    'pageSize' => 10,
                ]);

                if (!$response->ok()) {
                    $this->warn("    API error {$response->status()} — skipping");
                    continue;
                }

                foreach ($response->json('foods', []) as $food) {
                    if (count($foods) >= $target) break;

                    $nutrients = collect($food['foodNutrients'] ?? []);
                    $protein = $nutrients->firstWhere('nutrientName', 'Protein')['value'] ?? null;

                    if ($protein === null || $protein < 0.1) continue;

                    $name = $this->cleanName($food['description']);
                    $slug = $this->uniqueSlug($name, $slugsSeen);

                    $entry = [
                        'name'               => $name,
                        'slug'               => $slug,
                        'protein_per_100g'   => round($protein, 1),
                        'calories_per_100g'  => $this->nutrient($nutrients, 'Energy'),
                        'fat_per_100g'       => $this->nutrient($nutrients, 'Total lipid (fat)'),
                        'carbs_per_100g'     => $this->nutrient($nutrients, 'Carbohydrate, by difference'),
                        'fibre_per_100g'     => $this->nutrient($nutrients, 'Fiber, total dietary'),
                        'description'        => null,
                        'serving_size'       => null,
                        'protein_per_serving' => null,
                    ];

                    $foods[] = $entry;
                    $slugsSeen[$slug] = true;
                }

                // Write progress every 50 foods so a crash doesn't lose everything
                if (count($foods) % 50 === 0) {
                    $this->save($foods);
                    $this->line("    (saved checkpoint: " . count($foods) . " foods)");
                }

                usleep(200000);

            } catch (\Exception $e) {
                $this->warn("    Error: " . $e->getMessage());
            }
        }

        $this->save($foods);
        $this->info('Written ' . count($foods) . ' foods to database/seeders/data/foods.json');
        return 0;
    }

    private function save(array $foods): void
    {
        $sorted = $foods;
        usort($sorted, fn($a, $b) => $b['protein_per_100g'] <=> $a['protein_per_100g']);
        $path = database_path('seeders/data/foods.json');
        file_put_contents($path, json_encode(array_values($sorted), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }

    private function loadExisting(): array
    {
        $path = database_path('seeders/data/foods.json');
        if (!file_exists($path)) return [];
        return json_decode(file_get_contents($path), true) ?? [];
    }

    private function cleanName(string $name): string
    {
        return Str::title(strtolower($name));
    }

    private function uniqueSlug(string $name, array &$seen): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $n = 1;
        while (isset($seen[$slug])) {
            $slug = $base . '-' . $n++;
        }
        $seen[$slug] = true;
        return $slug;
    }

    private function nutrient(\Illuminate\Support\Collection $nutrients, string $name): ?float
    {
        $val = $nutrients->firstWhere('nutrientName', $name)['value'] ?? null;
        return $val !== null ? round($val, 1) : null;
    }
}
