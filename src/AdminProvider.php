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

        $this->publishes([
            __DIR__.'/../install-stubs/config/translatable.php' => config_path('translatable.php'),
        ], 'config');

        $this->app->register(ViewComposerProvider::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../install-stubs/config/translatable.php', 'translatable'
        );
    }
}
