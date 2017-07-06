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
        $this->loadRoutesFrom(__DIR__.'/MediaLibrary/Http/routes.php'); //FIXME:: ako sa routes prefixuju s brackets/admin?
        
        $this->publishes([
            __DIR__.'/MediaLibrary/config' => base_path('config')
        ], 'config');


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
        //FIXME: lepsie by bolo keby sa to dalo publishnut do filesystems
        $this->mergeConfigFrom(
            __DIR__.'/MediaLibrary/config/filesystems.php', 'filesystems.disks'
        );
    }
}
