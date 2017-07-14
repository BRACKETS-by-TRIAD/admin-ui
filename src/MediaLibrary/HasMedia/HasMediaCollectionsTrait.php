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
                return $collection->getName() == $file['collection'];
            })->first();

            if($collection) {
                if(isset($file['id']) && $file['id']) {
                    if(isset($file['deleted']) && $file['deleted']) {
                        if($medium = app(MediaModel::class)->find($file['id'])) {
                            $medium->delete();
                        }
                    }
                }
                else {
                    //path from config/disk
                    $this->addMedia(storage_path('app/'.$file['path']))->toMediaCollection($collection->getName(), $collection->getDisk());
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

    // FIXME should it be here?
    public function getMediaForUploadComponent(string $collection) {
        return $this->getMedia($collection)->map(function($medium) use ($collection) { 
            return [ 
                'id'         => $medium->id,
                //FIXME: ked to je file, tak nema square200, treba zobrazit len ikonku a nazov na frontende
                'path'       => $medium->getUrl(), 
                'collection' => $collection,
                'name'       => $medium->file_name, 
                'size'       => $medium->size
            ];
        });
    }
}