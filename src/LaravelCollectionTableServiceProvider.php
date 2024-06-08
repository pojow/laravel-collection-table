<?php

namespace Pojow\LaravelCollectionTable;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Pojow\LaravelCollectionTable\Console\Commands\MakeTable;
use Pojow\LaravelCollectionTable\View\TableComponent;

class LaravelCollectionTableServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Blade::component('laravel-collection-table', TableComponent::class);
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'laravel-collection-table');
        $this->publishes([
            __DIR__ . '/../config/laravel-collection-table.php' => config_path('laravel-collection-table.php'),
        ], 'laravel-collection-table:config');
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/laravel-collection-table'),
        ], 'laravel-collection-table:views');
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/laravel-collection-table.php', 'laravel-collection-table');
        $this->commands([
            MakeTable::class,
        ]);
    }
}
