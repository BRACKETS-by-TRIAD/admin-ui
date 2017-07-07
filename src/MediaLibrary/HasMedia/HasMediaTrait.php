<?php

namespace Brackets\Admin\MediaLibrary\HasMedia;

use Spatie\MediaLibrary\HasMedia\HasMediaTrait as ParentHasMediaTrait;
use Spatie\MediaLibrary\Media as MediaModel;
use Illuminate\Support\Collection;

trait HasMediaTrait {

    use ParentHasMediaTrait;

    public function processMedia(Collection $files) {
        $mediaCollections = $this->getCollections();

        $files->map(function($file) use ($mediaCollections) {
            if(isset($mediaCollections[$file['collection']])) {
                if(isset($file['id']) && $file['id']) {
                    //pokial mame id, tak ide o update
                    if(isset($file['deleted']) && $file['deleted']) {
                        //pokial mame deleted, tak ideme subor zmazat

                        if($medium = app(MediaModel::class)->find($file['id'])) {
                            $medium->delete();
                        }
                    }
                }
                else {
                    $this->addMedia($file['path'])->toMediaCollection($file['collection'], config('simpleweb-medialibrary.disc'));
                }
            }
        });
    }

    public function getMediaForUploadComponent(String $collection) {
        return $this->getMedia($collection)->map(function($medium) use ($collection) { 
            return [ 
                'id'         => $medium->id,
                //FIXME: ked to je file, tak nema square200, treba zobrazit len ikonku a nazov na frontende
                'path'       => $medium->getUrl('square200'), 
                'collection' => $collection,
                'name'       => $medium->file_name, 
                'size'       => $medium->size
            ];
        });
    }


    // FIXME: stare veci, refactor
    
    public function registerMediaConversions() {

        $collections = array_merge($this->getImageCollections(), $this->getGalleryCollections());
        foreach($collections as $collectionName => $collectionProperties) {

            $this->addMediaConversion('thumb')
                ->width(200)
                ->height(150)
                ->fit('crop', 200, 150)
                ->nonQueued()
                ->performOnCollections($collectionName);

            $this->addMediaConversion('square200')
                ->width(200)
                ->height(200)
                ->fit('crop', 200, 200)
                ->nonQueued()
                ->performOnCollections($collectionName);

            //FIXME: alebo staci pouzit ->preservingOriginal();
            $this->addMediaConversion('original')
                ->nonQueued()
                ->performOnCollections($collectionName);

            foreach($this->getConversions($collectionName) as $name => $properties) {
                if(empty($properties["w"]) && !empty($properties["width"])) {
                    $properties["w"] = $properties["width"];
                }
                if(empty($properties["h"]) && !empty($properties["height"])) {
                    $properties["h"] = $properties["height"];
                }
                if(empty($properties["fit"])) {
                    $properties["fit"] = 'crop';
                }
                $conversion = $this->addMediaConversion($name)
                    ->setManipulations($properties);
                if(!empty($properties["queue"]) && $properties["queue"]) {
                    $conversion->queued();
                } else {
                    $conversion->nonQueued();
                }
                $conversion->performOnCollections($collectionName);
            }

        }
    }

    public function getImageCollections()
    {
        $collections = [];
        foreach ($this->getCollections() as $key => $collection) {
            if($collection['type'] == 'image' ) {
                $collections[$key] = $collection;
            }
        }
        return $collections;
    }

    public function getGalleryCollections()
    {
        $collections = [];
        foreach ($this->getCollections() as $key => $collection) {
            if($collection['type'] == 'gallery' ) {
                $collections[$key] = $collection;
            }
        }
        return $collections;
    }

    public function getFileCollections()
    {
        $collections = [];
        foreach ($this->getCollections() as $key => $collection) {
            if($collection['type'] == 'file' ) {
                $collections[$key] = $collection;
            }
        }
        return $collections;
    }

    public function getVideoCollections()
    {
        $collections = [];
        foreach ($this->getCollections() as $key => $collection) {
            if($collection['type'] == 'video' ) {
                $collections[$key] = $collection;
            }
        }
        return $collections;
    }

    /**
     * Media conversions
     *
     * @return array
     */
    public function getConversions($collectionName = null) {
        $conversions = [];
        foreach ($this->getCollections() as $key => $collection) {
            if(!empty($collection['conversions'])) {
                if(!empty($collectionName) && $collectionName === $key ) {
                    $conversions = $collection['conversions'];
                } else if (empty($collectionName)){
                    array_merge($conversions, $collection['conversions']);
                }
            }
        }
        return $conversions;
    }

}