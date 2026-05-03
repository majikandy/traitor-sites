<?php

namespace App\Http\Controllers;

use App\Models\Recipe;

class RecipeController extends Controller
{
    public function index()
    {
        $currentCategory = request()->query('cat');
        $categories = config('toptoast.categories');

        $query = Recipe::query();

        if ($currentCategory) {
            $query->byCategory($currentCategory);
        }

        $recipes = $query->latest()->get();

        return view('recipes.index', [
            'recipes' => $recipes,
            'currentCategory' => $currentCategory,
            'categories' => $categories,
        ]);
    }

    public function show(string $slug)
    {
        $recipe = Recipe::where('slug', $slug)->firstOrFail();

        $moreRecipes = Recipe::where('slug', '!=', $slug)
            ->inRandomOrder()
            ->take(3)
            ->get();

        return view('recipes.show', [
            'recipe' => $recipe,
            'moreRecipes' => $moreRecipes,
        ]);
    }
}
