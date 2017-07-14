<?php

namespace Brackets\Admin\MediaLibrary\HasMedia;

use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;

interface HasMediaCollections extends HasMedia {

    /**
     * @return array
     */
    public function registerMediaCollections();

}