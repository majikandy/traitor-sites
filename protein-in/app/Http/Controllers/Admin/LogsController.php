<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class LogsController extends Controller
{
    public function index()
    {
        $logFile = storage_path('logs/laravel.log');
        $lines = [];

        if (file_exists($logFile)) {
            $all = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $lines = array_slice($all, -300); // last 300 lines
            $lines = array_reverse($lines);
        }

        return view('admin.logs', compact('lines'));
    }

    public function clear()
    {
        file_put_contents(storage_path('logs/laravel.log'), '');
        return back()->with('cleared', true);
    }
}
