<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;

class PostImportController extends Controller
{
    public function seed()
    {
        Artisan::call('db:seed', ['--class' => 'PostSeeder', '--force' => true]);
        return back()->with('seed_posts_result', Artisan::output());
    }
}
