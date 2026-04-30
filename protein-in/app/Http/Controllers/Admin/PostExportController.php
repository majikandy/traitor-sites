<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;

class PostExportController extends Controller
{
    public function store()
    {
        Artisan::call('posts:export-seed');
        return back()->with('post_export_result', Artisan::output());
    }
}
