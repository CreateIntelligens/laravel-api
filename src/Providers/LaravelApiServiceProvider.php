<?php

namespace Bleuren\LaravelApi\Providers;

use Bleuren\LaravelApi\Console\Commands\MakeRepositoryCommand;
use Bleuren\LaravelApi\Console\Commands\MakeServiceCommand;
use Illuminate\Support\ServiceProvider;

class LaravelApiServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeRepositoryCommand::class,
                MakeServiceCommand::class,
            ]);
        }

        $this->publishes([
            __DIR__.'/../../config/laravel-api.php' => config_path('laravel-api.php'),
        ], 'config');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/laravel-api.php', 'laravel-api'
        );
    }
}
