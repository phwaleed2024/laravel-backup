<?php

namespace Avcodewizard\LaravelBackup\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
    public function index()
    {
        $backupPath = storage_path('backups'); 

        if (!file_exists($backupPath)) {
            mkdir($backupPath, 0755, true);
        }
        $files = collect(scandir($backupPath))->filter(function ($file) use ($backupPath) {
            return is_file($backupPath . '/' . $file);
        })->sortByDesc(function ($file) use ($backupPath) {
            return filemtime($backupPath . '/' . $file); // Sort by last modified time (latest first)
        });

        $backups = [];

        foreach ($files as $file) {
            if (preg_match('/(\d{4}-\d{2}-\d{2}-\d{2}-\d{2}-\d{2})_(database|storage)\.(gz|sql|bak|zip)/', $file, $matches)) {
                $timestamp = $matches[1];
                $type = $matches[2];

                if (!isset($backups[$timestamp])) {
                    $backups[$timestamp] = [
                        'timestamp' => $timestamp,
                        'database' => null,
                        'storage' => null,
                    ];
                }

                if ($type === 'database') {
                    $backups[$timestamp]['database'] = $file;
                } elseif ($type === 'storage') {
                    $backups[$timestamp]['storage'] = $file;
                }
            }
        }

        return view('laravel-backup::backup_list', compact('backups'));
    }

    public function create()
    {
        Artisan::queue('backup:run');
        return redirect()->route('laravel-backup.index')->with('success', 'Backup creating in background please wait and refresh the page after few minutes!');
    }

    public function download(Request $request)
    {
        $file = $request->query('file');
        $filePath = storage_path("backups/{$file}");

        if (file_exists($filePath)) {
            return response()->download($filePath);
        }

        return redirect()->route('laravel-backup.index')->with('error', 'File not found!');
    }

    public function delete(Request $request)
    {
        $timestamp = $request->timestamp;
        $backupPath = storage_path('backups');

        $dbFile = "{$backupPath}/{$timestamp}_database.sql";
        $storageFile = "{$backupPath}/{$timestamp}_storage.zip";

        if (file_exists($dbFile)) {
            unlink($dbFile);
        }

        if (file_exists($storageFile)) {
            unlink($storageFile);
        }

        return redirect()->route('laravel-backup.index')->with('success', 'Backup deleted successfully!');
    }
}
