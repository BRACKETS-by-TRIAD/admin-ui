<?php

namespace Brackets\Admin\MediaLibrary\HasMedia;

use Spatie\MediaLibrary\HasMedia\HasMediaTrait as ParentHasMediaTrait;
use Spatie\MediaLibrary\Media as MediaModel;
use Illuminate\Support\Collection;

trait HasMediaCollectionsTrait {

    use ParentHasMediaTrait;

    /** @var  Collection */
    protected $mediaCollections;

    static $FILE_PROTECTION_LOGGED_IN = 'logged_in';
    static $FILE_PROTECTION_PERMISSION  = 'permission';
    static $FILE_PROTECTION_POLICY = 'policy';

    static $FILE_DISC_PUBLIC = 'media';
    static $FILE_DISC_PRIVATE = 'media-protected';

    public function processMedia(Collection $files) {
        $mediaCollections = $this->registerMediaCollections();

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

    public function addMediaCollection($name) : \Brackets\Admin\MediaLibrary\HasMedia\Collection {

        // FIXME this should be inited elswhere
        if (is_null($this->mediaCollections)) {
            $this->mediaCollections = collect();
        }

        $collection = \Brackets\Admin\MediaLibrary\HasMedia\Collection::create($name);

        $this->mediaCollections->push($collection);

        return $collection;
    }

    public function getMediaCollections() {
        return $this->mediaCollections;
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

    //defaultne hodnoty, overridne si clovek v modeli
    public function getFileDisc() {
        return self::$FILE_DISC_PUBLIC;
    }

    public function getFileUploadProtection() {
        return self::$FILE_PROTECTION_LOGGED_IN;
    }

    public function getFileViewProtection() {
        return self::$FILE_PROTECTION_LOGGED_IN;
    }
}