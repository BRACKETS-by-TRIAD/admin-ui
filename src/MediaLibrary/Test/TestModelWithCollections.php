<?php

namespace Brackets\Admin\MediaLibrary\Test;

class TestModelWithCollections extends TestModel
{
    /**
     * Media collections
     *
     */
    public function registerMediaCollections() {

        $this->addMediaCollection('gallery')
             ->title('Gallery')
             ->image() // only image can have conversions
             ->maxNumberOfFiles(20)
             ->maxFilesize(2*1024*1024)
             ->accepts('image/*');

        $this->addMediaCollection('documents')
             ->title('Documents')
             ->protected()
             ->canView('vop.view')
             ->canUpload('vop.upload')
             ->maxNumberOfFiles(20)
             ->maxFilesize(2*1024*1024)
             ->accepts('application/pdf, application/msword');
    }

    /**
     * Register the conversions that should be performed.
     *
     */
    public function registerMediaConversions() {
        $this->registerComponentThumbs();
        
        $this->addMediaConversion('thumb')
             ->width(368)
             ->height(232)
             ->sharpen(10)
             ->optimize()
             ->performOnCollections('gallery');
    }
}