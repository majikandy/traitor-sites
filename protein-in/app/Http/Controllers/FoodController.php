<?php

namespace App\Http\Controllers;

use App\Models\Food;

class FoodController extends Controller
{
    public function index()
    {
        $foods = Food::orderByDesc('protein_per_100g')->paginate(20);
        return view('foods.index', compact('foods'));
    }

    public function browse()
    {
        $foods = Food::orderBy('name')->paginate(50);
        return view('foods.browse', compact('foods'));
    }

    public function show(Food $food)
    {
        $food->load('categories', 'tags');
        return view('foods.show', compact('food'));
    }
}
