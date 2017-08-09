<?php namespace Brackets\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Config;
use Illuminate\Validation\Rule;

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

    public function rules()
    {
        $standardRules = collect($this->untranslatableRules());

        $rules = $this->getRequiredLocales()->flatMap(function($locale){
            return collect($this->translatableRules($locale['locale']))->mapWithKeys(function($rule, $ruleKey) use ($locale) {
                //TODO refactor
                if(!$locale['required']) {
                    if(is_array($rule) && ($key = array_search('required', $rule)) !== false) {
                        unset($rule[$key]);
                        array_push($rule, 'nullable');
                    } else if(is_string($rule)) {
                        $rule = str_replace('required', 'nullable', $rule);
                    }
                }
                return [$ruleKey.'.'.$locale['locale'] => $rule];
            });
        })->merge($standardRules);

        return $rules->toArray();
    }

    public function untranslatableRules() {
        return [];
    }

    public function translatableRules($locale) {
        return [];
    }

}