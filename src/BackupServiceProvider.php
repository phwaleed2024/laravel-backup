<?php

namespace Avcodewizard\LaravelBackup;

use Illuminate\Support\ServiceProvider;
use Avcodewizard\LaravelBackup\Commands\BackupCommand;

class BackupServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laravelBackup.php', 'laravelBackup');
    }

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-backup');

        // Publish config file
        $this->publishes([
            __DIR__.'/../config/laravelBackup.php' => config_path('laravelBackup.php'),
        ], 'laravel-backup');

        // Register command
        if ($this->app->runningInConsole()) {
            $this->commands([
                BackupCommand::class,
            ]);
        }
    }

    // composer json
    // "repositories": [
    //     {
    //         "type": "path",
    //         "url": "packages/avcodewizard/laravel-backup"
    //     }
    // ],
}
