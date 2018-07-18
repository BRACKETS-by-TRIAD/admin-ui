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
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'brackets/admin-ui');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../install-stubs/resources/assets' => resource_path('assets/admin')
            ], 'assets');

            $this->publishes([
                __DIR__.'/../install-stubs/resources/views' => resource_path('views')
            ], 'views');

            $this->publishes([
                __DIR__ . '/../install-stubs/config/wysiwyg-media.php' => config_path('wysiwyg-media.php'),
            ], 'config');

            if (!glob(base_path('database/migrations/*_create_wysiwyg_media_table.php'))) {
                $this->publishes([
                    __DIR__ . '/../install-stubs/database/migrations/create_wysiwyg_media_table.php' => database_path('migrations').'/2018_07_18_000000_create_wysiwyg_media_table.php',
                ], 'migrations');
            }
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
