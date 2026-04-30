<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SearchQueryController;
use App\Http\Controllers\Admin\FoodImportController;
use App\Http\Controllers\Admin\PostImportController;
use App\Http\Controllers\Admin\PostExportController;

Route::get('/', [FoodController::class, 'index']);
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// Food search — fully linkable path-based URLs
Route::get('/search/{query}', [SearchController::class, 'show'])->name('search.show');
Route::get('/search', [SearchController::class, 'index'])->name('search.index');

// Individual food pages
Route::get('/foods', [FoodController::class, 'browse'])->name('foods.browse');
Route::get('/foods/{food:slug}', [FoodController::class, 'show'])->name('foods.show');

// Taxonomy — dynamic, shared by foods + posts
Route::get('/category/{category:slug}', [CategoryController::class, 'show'])->name('category.show');
Route::get('/tag/{tag:slug}', [TagController::class, 'show'])->name('tag.show');

// Admin
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/searches', [SearchQueryController::class, 'index'])->name('searches');
    Route::post('/import', [FoodImportController::class, 'store'])->name('import');
    Route::post('/import-posts', [PostImportController::class, 'store'])->name('import-posts');
    Route::post('/export-posts', [PostExportController::class, 'store'])->name('export-posts');
});

// Blog
Route::get('/blog', [PostController::class, 'index'])->name('blog.index');

// Blog posts — preserves old WordPress URL structure exactly
Route::get('/{year}/{month}/{day}/{slug}', [PostController::class, 'show'])
    ->name('post.show')
    ->where(['year' => '\d{4}', 'month' => '\d{2}', 'day' => '\d{2}']);
