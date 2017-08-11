<?php

namespace Brackets\Admin\Traits;

use Illuminate\Database\Eloquent\JsonEncodingException;
use Illuminate\Support\Facades\App;
use Spatie\Translatable\HasTranslations as ParentHasTranslations;

trait HasTranslations
{
    use ParentHasTranslations;

    protected $locale;
    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getAttributeValue($key)
    {
        if (!$this->isTranslatableAttribute($key)) {
            return parent::getAttributeValue($key);
        }

        return $this->getTranslation($key, $this->getLocale());
    }

    public function setLocale($locale) {
        $this->locale = $locale;
    }

    public function getLocale()
    {
        return !is_null($this->locale) ? $this->locale : App::getLocale();
    }

    public function toArray()
    {
        $array = parent::toArray();
        collect($this->getTranslatableAttributes())->map(function($attribute) use (&$array) {
            $array[$attribute] = $this->getAttributeValue($attribute);
        });
        return $array;
    }

    public function toJsonAllLocales($options = 0)
    {
        $json = json_encode(parent::toArray(), $options);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw JsonEncodingException::forModel($this, json_last_error_msg());
        }

        return $json;
    }
}
