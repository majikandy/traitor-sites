<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class MigrationsController extends Controller
{
    public function index()
    {
        // Migrations recorded in the DB (ran successfully)
        $ran = DB::table('migrations')->orderBy('batch')->orderBy('migration')->get();
        $ranNames = $ran->pluck('migration')->toArray();

        // Migration files on disk
        $files = collect(File::files(database_path('migrations')))
            ->map(fn($f) => pathinfo($f->getFilename(), PATHINFO_FILENAME))
            ->sort()
            ->values();

        $pending = $files->filter(fn($name) => !in_array($name, $ranNames))->values();

        return view('admin.migrations', compact('ran', 'pending'));
    }

    public function run()
    {
        \Artisan::call('migrate', ['--force' => true]);
        $output = \Artisan::output();

        return back()->with('migrate_output', $output ?: 'Nothing to migrate.');
    }
}
