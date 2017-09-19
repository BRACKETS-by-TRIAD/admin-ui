<?php namespace Brackets\AdminUI;

use Brackets\AdminUI\Console\Commands\AdminUIInstall;
use Illuminate\Support\ServiceProvider;

class AdminUIServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->commands([
            AdminUIInstall::class,
        ]);

        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'brackets/admin-ui');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'brackets/admin-ui');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../install-stubs/resources/assets' => resource_path('assets/admin')
            ], 'assets');

            $this->publishes([
                __DIR__.'/../install-stubs/resources/views' => resource_path('views')
            ], 'views');
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
