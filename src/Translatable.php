<?php

namespace Brackets\Admin;

use Illuminate\Support\Facades\Config;

class Translatable
{
    /**
     * Attempt to get all locales.
     *
     * @return array
     */
    public function getLocales()
    {
        return collect((array) Config::get('translatable.locales'))->map(function($val, $key){
            return is_array($val) ? $key : $val;
        });
    }
}
