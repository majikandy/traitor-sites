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
use App\Http\Controllers\Admin\MigrationsController;
use App\Http\Controllers\Admin\LogsController;
use App\Http\Controllers\Admin\NutritionBackfillController;

Route::get('/', [FoodController::class, 'index']);
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// Food search — fully linkable path-based URLs
Route::get('/search/{query}', [SearchController::class, 'show'])->name('search.show');
Route::post('/search/{query}/backfill', [SearchController::class, 'backfill'])->name('search.backfill');
Route::get('/search', [SearchController::class, 'index'])->name('search.index');

// Individual food pages
Route::get('/foods', [FoodController::class, 'browse'])->name('foods.browse');
Route::get('/foods/{food:slug}', [FoodController::class, 'show'])->name('foods.show');
Route::get('/foods/{food:slug}/image', [FoodController::class, 'fetchImage'])->name('foods.image');

// Taxonomy — dynamic, shared by foods + posts
Route::get('/category/{category:slug}', [CategoryController::class, 'show'])->name('category.show');
Route::get('/tag/{tag:slug}', [TagController::class, 'show'])->name('tag.show');

// Admin login (no auth required — handles the POST login submission)
Route::get('/admin/login', fn() => view('admin.login', ['error' => null, 'redirect' => '/admin']))->name('admin.login');
Route::post('/admin/login', function (\Illuminate\Http\Request $request) {
    $secret = config('app.admin_shared_secret');
    $redirect = $request->input('redirect', '/admin');
    if ($secret && hash_equals($secret, $request->input('admin_password', ''))) {
        $request->session()->put('admin_authed_at', now()->timestamp);
        return redirect($redirect);
    }
    return response(view('admin.login', ['error' => 'Wrong password.', 'redirect' => $redirect]), 401);
})->name('admin.login.post');

// Admin
Route::prefix('admin')->name('admin.')->middleware(\App\Http\Middleware\AdminAuth::class)->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/searches', [SearchQueryController::class, 'index'])->name('searches');
    Route::post('/import', [FoodImportController::class, 'store'])->name('import');
    Route::post('/import-posts', [PostImportController::class, 'store'])->name('import-posts');
    Route::post('/export-posts', [PostExportController::class, 'store'])->name('export-posts');
    Route::get('/migrations', [MigrationsController::class, 'index'])->name('migrations');
    Route::post('/migrations/run', [MigrationsController::class, 'run'])->name('migrations.run');
    Route::get('/nutrition-backfill', [NutritionBackfillController::class, 'index'])->name('nutrition-backfill');
    Route::post('/nutrition-backfill/run', [NutritionBackfillController::class, 'run'])->name('nutrition-backfill.run');
    Route::get('/logs', [LogsController::class, 'index'])->name('logs');
    Route::post('/logs/clear', [LogsController::class, 'clear'])->name('logs.clear');
});

// Blog
Route::get('/blog', [PostController::class, 'index'])->name('blog.index');

// Blog posts — preserves old WordPress URL structure exactly
Route::get('/{year}/{month}/{day}/{slug}', [PostController::class, 'show'])
    ->name('post.show')
    ->where(['year' => '\d{4}', 'month' => '\d{2}', 'day' => '\d{2}']);
