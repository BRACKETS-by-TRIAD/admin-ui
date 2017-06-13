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
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'brackets/admin');

        $this->publishes([
            __DIR__.'/../resources/assets/js' => resource_path('assets/js')
        ], 'assets');

        // TODO register also CSS
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
