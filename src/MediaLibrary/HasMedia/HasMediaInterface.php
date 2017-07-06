<?php

namespace Brackets\Admin\MediaLibrary\HasMedia;

use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;

interface HasMediaInterface extends HasMediaConversions {

    /**
     * @return array
     */
    public function getCollections();

    /**
     * @return array
     */
    public function getConversions();

}