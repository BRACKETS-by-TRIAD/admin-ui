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

    // FIXME should it be here?
    static $FILE_PROTECTION_LOGGED_IN = 'logged_in';
    static $FILE_PROTECTION_PERMISSION  = 'permission';
    static $FILE_PROTECTION_POLICY = 'policy';

    // FIXME should it be here?
    static $FILE_DISC_PUBLIC = 'media';
    static $FILE_DISC_PRIVATE = 'media-protected';

    public function processMedia(Collection $files) {
        $mediaCollections = $this->getMediaCollections();

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
                    // $this->addMedia(storage_path('app/'.$file['path']))->toMediaCollection($file['collection'], config('simpleweb-medialibrary.disc'));
                    $this->addMedia(storage_path('app/'.$file['path']))->toMediaCollection($file['collection'], $this->getFileDisc());
                }
            }
        });
    }

    public static function bootHasMediaCollectionsTrait() {
        // FIXME let's try if this works
        static::saving(function($model, Request $request)
        {
            $model->processMedia($request->files());
        });
    }

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
                'path'       => $medium->getUrl('square200'), 
                'collection' => $collection,
                'name'       => $medium->file_name, 
                'size'       => $medium->size
            ];
        });
    }

    // FIXME should it be here?
    //defaultne hodnoty, overridne si clovek v modeli
    public function getFileDisc() {
        return self::$FILE_DISC_PUBLIC;
    }

    // FIXME should it be here?
    public function getFileUploadProtection() {
        return self::$FILE_PROTECTION_LOGGED_IN;
    }

    // FIXME should it be here?
    public function getFileViewProtection() {
        return self::$FILE_PROTECTION_LOGGED_IN;
    }
}