<?php

namespace Brackets\Admin\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Brackets\AdminTraslations\Auth\Activations\ActivationBroker
 */
class Translatable extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'translatable';
    }
}
