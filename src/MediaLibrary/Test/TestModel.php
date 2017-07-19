<?php

namespace Brackets\Admin\MediaLibrary\Test;

use Illuminate\Database\Eloquent\Model;
// use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
// use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;

use Brackets\Admin\MediaLibrary\HasMedia\HasMediaCollections;
use Brackets\Admin\MediaLibrary\HasMedia\HasMediaCollectionsTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;

class TestModel extends Model implements HasMediaConversions, HasMediaCollections
{
    use HasMediaCollectionsTrait;

    protected $table = 'test_models';
    protected $guarded = [];
    public $timestamps = false;

    /**
     * Media collections
     *
     */
    public function registerMediaCollections() {
        
    }

    /**
     * Register the conversions that should be performed.
     *
     */
    public function registerMediaConversions() {
        
    }
}