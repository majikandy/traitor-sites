<?php

namespace App\Http\Controllers;

use App\Models\Recipe;

class HomeController extends Controller
{
    public function index()
    {
        $recipes = Recipe::latest()->take(6)->get();

        return view('home', ['recipes' => $recipes]);
    }

    public function about()
    {
        return view('about');
    }
}
