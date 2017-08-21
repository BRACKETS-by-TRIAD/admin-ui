<?php namespace Brackets\Admin;

use Brackets\Admin\Facades\Translatable;
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
            __DIR__.'/../install-stubs/resources/assets' => resource_path('assets/admin')
        ], 'assets');

        $this->publishes([
            __DIR__.'/../install-stubs/resources/views' => resource_path('views')
        ], 'views');

        $this->publishes([
            __DIR__.'/../install-stubs/config/translatable.php' => config_path('translatable.php'),
        ], 'config');

        $this->app->register(ViewComposerProvider::class);
        $this->app->register(TranslatableServiceProvider::class);
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

        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('Translatable', Translatable::class);
    }
}
