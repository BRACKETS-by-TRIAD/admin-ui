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
                    $this->addMedia(storage_path('app/'.$file['path']))->toMediaCollection($file['collection'], config('simpleweb-medialibrary.disc'));
                }
            }
        });
    }

    public function getMediaForUploadComponent(string $collection) {
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
    
    public function registerMediaConversions() {

        $this->getImageCollections()->map(function($collectionProperties, $collectionName) {
            //FIXME: upload thumb pri editaci, tam nam zatial nefunguje browser resize 
            $this->addMediaConversion('square200')
                ->width(200)
                ->height(200)
                ->fit('crop', 200, 200)
                ->nonQueued()
                ->performOnCollections($collectionName);

            $this->addMediaConversion('original')
                 ->nonQueued()
                 ->performOnCollections($collectionName);

            $this->getConversions($collectionName)->map(function($conversionProperties, $conversionName) use ($collectionName) {

                $mediaConversion = $this->addMediaConversion($conversionName);
                   
                if(!empty($conversionProperties["width"])) {
                    $mediaConversion->width($conversionProperties["width"]);
                }

                if(!empty($conversionProperties["height"])) {
                    $mediaConversion->height($conversionProperties["height"]);
                }

                //FIXME: https://docs.spatie.be/image/v1/introduction
                //setManipulations je deprecated, cize teraz treba vsetky manipulations definovat explicitne tu

                if(!empty($conversionProperties["fit"]) && !empty($conversionProperties["width"]) && !empty($conversionProperties["height"])) {
                    $mediaConversion->fit($conversionProperties["fit"], $conversionProperties["width"], $conversionProperties["height"]);
                }

                if(!empty($conversionProperties["queue"]) && $conversionProperties["queue"]) {
                    $mediaConversion->queued();
                } else {
                    $mediaConversion->nonQueued();
                }
              
                $mediaConversion->performOnCollections($collectionName);     
            });
        });
    }

    public function getImageCollections(): Collection {
        return collect($this->getCollections())->filter(function($collection) {
            return $collection['type'] == 'image';
        });
    }

    public function getFileCollections(): Collection {
        return collect($this->getCollections())->filter(function($collection) {
            return $collection['type'] == 'file';
        });
    }

    public function getVideoCollections(): Collection {
        return collect($this->getCollections())->filter(function($collection) {
            return $collection['type'] == 'video';
        });
    }

    /**
     * Media conversions
     *
     * @return Collection
     */

    public function getConversions(string $collectionName): Collection {
        $conversions = array_get($this->getCollections(), $collectionName.'.conversions');

        return $conversions ? collect($conversions) : collect([]);
    }
}