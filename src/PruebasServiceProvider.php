<?php

namespace Raultm\Pruebas;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;

class PruebasServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'raultm');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'lumki');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/routes.php');

        $this->registerBladeDirectives();
        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/pruebas.php', 'pruebas');


        // Register the service the package provides.
        $this->app->singleton('pruebas', function ($app) {
            return new Pruebas;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['pruebas'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/pruebas.php' => config_path('pruebas.php'),
        ], 'pruebas.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/raultm'),
        ], 'pruebas.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/raultm'),
        ], 'pruebas.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/raultm'),
        ], 'pruebas.views');*/

        // Registering package commands.
        // $this->commands([]);
    }

    protected function registerBladeDirectives()
    {
        Blade::directive('lumki', function () {
            return
            Blade::compileString('<div class="block px-6 py-2 text-xs text-gray-400">Lumki</div>'

                . '<a href="{{ route(\'lumki.index\') }}" class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">'
                    . '{{ __(\'Users Management\') }}'
                . '</a>'
                . '@impersonating'
                    . '<a href="{{ route(\'impersonate.leave\') }}" class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">'
                        . '{{ __(\'Leave impersonation\') }}'
                    . '</a>'
                . '@endImpersonating'
            );
        });
    }
}
