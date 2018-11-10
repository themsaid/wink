<?php

namespace Wink;

use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class WinkServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerRoutes();
        $this->registerPublishing();

        $this->loadViewsFrom(
            __DIR__.'/../resources/views', 'wink'
        );
    }

    /**
     * Register the package routes.
     *
     * @return void
     */
    private function registerRoutes()
    {
        Route::namespace('Wink\Http\Controllers')
            ->middleware(['web', Authenticate::class])
            ->as('wink.')
            ->prefix(config('wink.path'))
            ->group(function () {
                $this->loadRoutesFrom(__DIR__.'/Http/routes.php');
            });
    }

    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    private function registerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $timestamp = date('Y_m_d_His');

            $this->publishes([
                __DIR__.'/../public' => public_path('vendor/wink'),
            ], 'wink-assets');

            $this->publishes([
                __DIR__.'/../config/wink.php' => config_path('wink.php'),
            ], 'wink-config');

            $this->publishes([
                __DIR__ . '/../database/migrations/create_wink_tables.php.stub' => database_path("migrations/{$timestamp}_create_wink_tables.php"),
            ], 'wink-migrations');
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/wink.php', 'wink'
        );

        $this->commands([
            Console\InstallCommand::class,
        ]);
    }
}
