<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;

class PostImportController extends Controller
{
    public function store()
    {
        Artisan::call('posts:import');
        return back()->with('post_import_result', Artisan::output());
    }
}
