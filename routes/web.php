<?php

use Avcodewizard\LaravelBackup\Http\Controllers\BackupController;
use Illuminate\Support\Facades\Route;

$middleware = ['web',\Avcodewizard\LaravelBackup\Http\Middleware\CheckLaravelBackupAccess::class];

Route::prefix('laravel-backup')
    ->middleware($middleware)
    ->name('laravel-backup.')
    ->group(function () {
        Route::get('/', [BackupController::class, 'index'])->name('index');
        Route::get('/create', [BackupController::class, 'create'])->name('create');
        Route::get('/download', [BackupController::class, 'download'])->name('download');
        Route::delete('/delete', [BackupController::class, 'delete'])->name('delete');
    });
