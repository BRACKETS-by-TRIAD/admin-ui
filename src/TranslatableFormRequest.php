<?php namespace Brackets\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Config;

class TranslatableFormRequest extends FormRequest {

    public function getLocales()
    {
        return collect((array) Config::get('translatable.locales'))->map(function($val, $key){
            return is_array($val) ? $key : $val;
        });

    }

    public function getRequiredLocales()
    {
        $locales = $this->getLocales();

        // let's make only default language required and make all others optional (feel free to delete if it's your case)
        return $locales->map(function($locale) use ($locales) {
            return [
                'locale' => $locale,
                'required' => $locale === $locales->first(),
            ];
        });
    }
}