<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\PriceIndexController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index']);

Route::get('/recipes', [RecipeController::class, 'index'])->name('recipes.index');
Route::get('/recipes/{slug}', [RecipeController::class, 'show'])->name('recipes.show');

Route::get('/recipes.php', fn () => redirect('/recipes', 301));
Route::get('/about.php', fn () => redirect('/about', 301));
Route::get('/recipes/{slug}.php', fn (string $slug) => redirect("/recipes/{$slug}", 301));

Route::get('/about', [HomeController::class, 'about'])->name('about');

Route::prefix('price-index')->name('price-index.')->group(function () {
    Route::get('/', [PriceIndexController::class, 'index'])->name('index');
    Route::get('/ingredient/{slug}', [PriceIndexController::class, 'ingredient'])->name('ingredient');
    Route::get('/api/chart/{slug}', [PriceIndexController::class, 'chartData'])->name('chart-data');
});
