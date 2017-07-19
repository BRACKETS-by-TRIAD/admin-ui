<?php

namespace Brackets\Admin\MediaLibrary\HasMedia;

use Illuminate\Http\Request;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait as ParentHasMediaTrait;
use Spatie\MediaLibrary\Media as MediaModel;
use Illuminate\Support\Collection;

trait HasMediaCollectionsTrait {

    use ParentHasMediaTrait;

    /** @var  Collection */
    protected $mediaCollections;

    public function processMedia(Collection $files) {
        $mediaCollections = $this->getMediaCollections();

        $files->each(function($file) use ($mediaCollections) {
            $collection = $mediaCollections->filter(function($collection) use ($file){
                return $collection->name == $file['collection'];
            })->first();

            if($collection) {
                if(isset($file['id']) && $file['id']) {
                    if(isset($file['deleted']) && $file['deleted']) {
                        if($medium = app(MediaModel::class)->find($file['id'])) {
                            $medium->delete();
                        }
                    }
                    else {
                        //TODO: update meta data?
                    }
                }
                else {
                    $metaData = [];
                    if(isset($file['name'])) {
                        $metaData['name'] = $file['name'];
                    }

                    if(isset($file['width'])) {
                        $metaData['width'] = $file['width'];
                    }

                    if(isset($file['height'])) {
                        $metaData['height'] = $file['height'];
                    }

                    //FIXME: upload path from config?
                    $this->addMedia(storage_path('app/'.$file['path']))
                         ->withCustomProperties($metaData)
                         ->toMediaCollection($collection->name, $collection->disk);
                }
            }
        });
    }

    // public static function bootHasMediaCollectionsTrait() {
    //     // FIXME let's try if this works
    //     static::saving(function($model, Request $request)
    //     {
    //         $model->processMedia($request->files());
    //     });
    // }

    protected function initMediaCollections() {
        $this->mediaCollections = collect();

        $this->registerMediaCollections();
    }

    public function addMediaCollection($name) : \Brackets\Admin\MediaLibrary\HasMedia\Collection {
        $collection = \Brackets\Admin\MediaLibrary\HasMedia\Collection::create($name);

        $this->mediaCollections->push($collection);

        return $collection;
    }

    public function getMediaCollections() {
        if (is_null($this->mediaCollections)) {
            $this->initMediaCollections();
        }

        return $this->mediaCollections;
    }

    public function getMediaCollection($collectionName) {
        $foundCollections = $this->getMediaCollections()->filter(function($collection) use ($collectionName){
            return $collection->name == $collectionName;
        });

        return $foundCollections->count() > 0 ? $foundCollections->first() : false;
    }

    public function getImageMediaCollections() {
        return $this->getMediaCollections()->filter(function($collection){
            return $collection->is_image;
        });
    }

    // FIXME should it be here?
    public function getMediaForUploadComponent(string $collectionName) {
        $collection = $this->getMediaCollection($collectionName);
   
        return $this->getMedia($collectionName)->map(function($medium) use ($collection) { 
            return [ 
                'id'         => $medium->id,
                'url'        => $medium->getUrl(),
                'thumb_url'  => $collection->is_image ? $medium->getUrl('square200') : $medium->getUrl(), 
                'type'       => $medium->mime_type,
                'collection' => $collection->name,
                'name'       => $medium->hasCustomProperty('name') ? $medium->getCustomProperty('name') : $medium->file_name, 
                'size'       => $medium->size
            ];
        });
    }

    //FIXME: this definitely shouldn't be here
    public function registerComponentThumbs() {
        $this->getImageMediaCollections()->each(function($collection) {
            $this->addMediaConversion('square200')
                 ->width(200)
                 ->height(200)
                 ->fit('crop', 200, 200)
                 ->optimize()
                 ->performOnCollections($collection->name);
        });
    }
}