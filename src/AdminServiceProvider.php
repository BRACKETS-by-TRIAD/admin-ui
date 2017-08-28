<?php namespace Brackets\Admin;

use Illuminate\Support\ServiceProvider;

class AdminServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'brackets/admin');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'brackets/admin');

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
