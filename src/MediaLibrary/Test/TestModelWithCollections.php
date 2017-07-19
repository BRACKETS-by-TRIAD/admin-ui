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
             ->title('Title')
             ->image() // only image can have conversions
             ->maxNumberOfFiles(20)
             ->maxFilesize(2*1024*1024)
             ->accepts('image/*');

        $this->addMediaCollection('vop')
             ->title('Vop signature')
             ->maxNumberOfFiles(20)
             ->maxFilesize(2*1024*1024)
             // ->accepts('application/pdf, application/msword')
             // ->accepts('*')
             ->protected();
             // ->canView('vop.view')
             // ->canUpload('vop.upload');

        $this->addMediaCollection('measurement_files')
             ->title('Measurement files');
             // ->accepts('application/pdf, application/msword');
             
            // ->protected()
            // ->disk($disk)
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