<?php namespace Brackets\Admin;

use Brackets\AdminGenerator\Generate\ModelFactory;
use Illuminate\Support\ServiceProvider;

class AdminProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'brackets/admin');

        $this->publishes([
            __DIR__.'/../install-stubs/resources/assets' => resource_path('assets')
        ], 'assets');

        $this->publishes([
            __DIR__.'/../install-stubs/resources/views' => resource_path('views')
        ], 'views');

//        $this->app->register(\Dimsav\Translatable\TranslatableServiceProvider::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
