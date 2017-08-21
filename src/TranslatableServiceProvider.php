<?php

namespace Brackets\Admin;

use Illuminate\Support\ServiceProvider;

class TranslatableServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerTranslatable();
    }

    /**
     * Register the password broker instance.
     *
     * @return void
     */
    protected function registerTranslatable()
    {
        $this->app->singleton('translatable', function ($app) {
            return new Translatable($app);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['translatable'];
    }
}
