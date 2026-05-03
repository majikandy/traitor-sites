<?php

namespace Database\Seeders;

use App\Models\Recipe;
use Illuminate\Database\Seeder;

class RecipeSeeder extends Seeder
{
    public function run(): void
    {
        $recipes = [
            [
                'title' => 'Smashed Avo & Chilli',
                'slug' => 'smashed-avo-chilli',
                'category' => 'savoury',
                'time_minutes' => 10,
                'difficulty' => 'Easy',
                'description' => 'The classic. Done properly, with a hit of chilli that wakes everything up.',
                'hero_color' => '#2d7a2d',
                'emoji_html' => '&#x1F951;',
                'ingredients_json' => [
                    '1 ripe avocado',
                    '2 slices sourdough',
                    '½ lemon, juiced',
                    '1 red chilli, finely sliced',
                    'Flaky sea salt',
                    'Extra virgin olive oil',
                    'Optional: poached egg',
                ],
                'steps_json' => [
                    'Toast your sourdough until properly golden — you should hear it when you tap it.',
                    'Halve the avocado, remove the stone, and scoop the flesh into a bowl.',
                    'Add lemon juice and a generous pinch of flaky salt. Smash with a fork — keep it chunky, not smooth.',
                    "Pile onto the hot toast. Don't spread it too carefully. Be generous.",
                    "Top with sliced chilli, a drizzle of olive oil, and more salt. Add a poached egg if you're doing this properly.",
                ],
                'tip' => "The avocado must be ripe. Press gently near the stem — it should give slightly. If it doesn't, wait a day.",
            ],
            [
                'title' => 'Ricotta, Honey & Fig',
                'slug' => 'ricotta-honey-fig',
                'category' => 'sweet',
                'time_minutes' => 8,
                'difficulty' => 'Easy',
                'description' => 'Creamy, sweet, and sophisticated. Breakfast that tastes like it should cost more.',
                'hero_color' => '#8b4513',
                'emoji_html' => '&#x1F353;',
                'ingredients_json' => [
                    '2 slices sourdough or brioche',
                    '4 tbsp ricotta',
                    '2 fresh figs, quartered',
                    '2 tsp runny honey',
                    'Pinch of flaky salt',
                    'Fresh thyme (optional)',
                ],
                'steps_json' => [
                    'Toast the bread until just golden — you want it to hold the ricotta without going soggy.',
                    "Spread ricotta generously. Don't be precious about it.",
                    'Arrange the fig quarters on top.',
                    'Drizzle honey over everything — be generous.',
                    'Finish with flaky salt and a few thyme leaves if using.',
                ],
                'tip' => "If figs aren't in season, ripe pear or sliced strawberries work beautifully.",
            ],
            [
                'title' => 'Garlic Mushroom & Thyme',
                'slug' => 'garlic-mushroom-thyme',
                'category' => 'savoury',
                'time_minutes' => 15,
                'difficulty' => 'Medium',
                'description' => 'Proper umami depth. The toast that makes people think you can actually cook.',
                'hero_color' => '#5c3d2e',
                'emoji_html' => '&#x1F344;',
                'ingredients_json' => [
                    '2 slices thick sourdough',
                    '300g mixed mushrooms',
                    '3 cloves garlic, sliced',
                    '4 sprigs fresh thyme',
                    '30g butter',
                    'Splash of dry white wine or stock',
                    'Flaky sea salt',
                    'Black pepper',
                ],
                'steps_json' => [
                    'Get a wide pan very hot. Add the butter and let it foam.',
                    "Add mushrooms in a single layer. Don't stir — let them colour for 2 minutes.",
                    'Add garlic and thyme. Toss and cook for another 2 minutes.',
                    'Add wine or stock. Let it bubble and reduce until almost gone.',
                    'Season aggressively. Toast your bread while the mushrooms rest for 1 minute.',
                    'Pile everything onto toast. Spoon any remaining pan juices over the top.',
                ],
                'tip' => "The most important step is the hot pan. If the mushrooms steam rather than fry, they'll be rubbery. Work in batches if needed.",
            ],
            [
                'title' => 'Cinnamon French Toast',
                'slug' => 'cinnamon-french-toast',
                'category' => 'brunch',
                'time_minutes' => 12,
                'difficulty' => 'Easy',
                'description' => 'Custardy, golden, and worth the extra dishes. The weekend toast.',
                'hero_color' => '#c8860a',
                'emoji_html' => '&#x1F35E;',
                'ingredients_json' => [
                    '2 thick slices brioche or white bread',
                    '2 eggs',
                    '60ml whole milk',
                    '1 tsp cinnamon',
                    '1 tsp vanilla extract',
                    '1 tbsp butter',
                    'Maple syrup to serve',
                    'Berries or banana to serve',
                ],
                'steps_json' => [
                    'Whisk eggs, milk, cinnamon, and vanilla in a shallow bowl.',
                    'Soak each bread slice for 30 seconds per side. It should be saturated but not falling apart.',
                    'Melt butter in a non-stick pan over medium heat.',
                    'Cook soaked bread for 2-3 minutes per side until deeply golden.',
                    'Serve immediately with maple syrup and fresh fruit.',
                ],
                'tip' => 'Day-old brioche absorbs the custard better than fresh. If you can plan ahead, leave it out overnight.',
            ],
            [
                'title' => 'Peanut Butter & Banana',
                'slug' => 'peanut-butter-banana',
                'category' => 'quick',
                'time_minutes' => 5,
                'difficulty' => 'Easy',
                'description' => 'Fuel disguised as a treat. Five minutes, maximum satisfaction.',
                'hero_color' => '#d4851a',
                'emoji_html' => '&#x1F34C;',
                'ingredients_json' => [
                    '2 slices wholegrain bread',
                    '3 tbsp natural peanut butter',
                    '1 ripe banana',
                    '1 tsp honey',
                    'Pinch of flaky salt',
                    'Optional: dark chocolate shavings',
                ],
                'steps_json' => [
                    'Toast the bread until properly done — wholegrain needs the full toast.',
                    'Spread peanut butter thickly while the toast is still hot.',
                    'Slice the banana and layer it generously.',
                    'Drizzle with honey and finish with flaky salt.',
                    "Add chocolate shavings if you're feeling indulgent.",
                ],
                'tip' => 'Natural peanut butter only — the kind where the oil separates. The smooth processed stuff is too sweet.',
            ],
            [
                'title' => 'Burrata, Tomato & Basil',
                'slug' => 'burrata-tomato-basil',
                'category' => 'savoury',
                'time_minutes' => 10,
                'difficulty' => 'Easy',
                'description' => 'Summer on toast. When tomatoes are good, this is better than it has any right to be.',
                'hero_color' => '#c0392b',
                'emoji_html' => '&#x1F345;',
                'ingredients_json' => [
                    '2 slices sourdough or ciabatta',
                    '125g burrata',
                    '3-4 ripe tomatoes',
                    'Fresh basil',
                    'Extra virgin olive oil',
                    'Flaky sea salt',
                    'Black pepper',
                    'Optional: balsamic glaze',
                ],
                'steps_json' => [
                    'Slice tomatoes and season well. Let them sit for 5 minutes — they\'ll release juices.',
                    'Toast bread until golden.',
                    "Tear the burrata over the toast — don't place it carefully.",
                    'Layer the seasoned tomatoes over and around the burrata.',
                    'Drizzle generously with olive oil. Add basil leaves.',
                    'Finish with more salt, pepper, and balsamic if using.',
                ],
                'tip' => 'This only works with properly ripe tomatoes. Out of season, roast cherry tomatoes at 180°C for 20 minutes first.',
            ],
        ];

        foreach ($recipes as $recipe) {
            Recipe::updateOrCreate(['slug' => $recipe['slug']], $recipe);
        }
    }
}
