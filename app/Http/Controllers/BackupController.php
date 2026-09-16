<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;

class BackupController extends Controller
{
    public function download()
    {
        $dbPath = database_path('database.sqlite');

        if (! File::exists($dbPath)) {
            return redirect()->back()->with('error', 'File database SQLite tidak ditemukan!');
        }

        $filename = 'backup_inventory_'.date('Y-m-d_H-i-s').'.sqlite';

        return response()->download($dbPath, $filename);
    }
}
