<?php

namespace Brackets\Admin\MediaLibrary\HasMedia;

class Collection  {

    protected $is_image = false;
    protected $name;
    protected $disk;
    protected $maxNumberOfFiles;
    protected $maxFilesize;
    protected $accepts;
    protected $canView;
    protected $canUpload;
    protected $viewPermission;
    protected $uploadPermission;


    public function __construct(string $name)
    {
        $this->name = $name;
        $this->disk = config('simpleweb-medialibrary.default_public_disk', 'media');
    }

    public static function create(string $name)
    {
        return new static($name);
    }

    // FIXME should it be here?
    public function title($title) {
        $this->title = $title;
        return $this;
    }

    /**
     * Set this collection contains an images. This allows the conversions functionality.
     *
     * @return $this
     */
    public function image() {
        $this->is_image = true;
        return $this;
    }

    /**
     * Specify a disk where to store this collection
     *
     * @param $disk
     * @return $this
     */
    public function disk($disk) {
        $this->disk = $disk;
        return $this;
    }

    /**
     * Alias to setting default protected disk
     *
     * @return $this
     */
    public function protected() {
        $this->disk = config('simpleweb-medialibrary.default_protected_disk');
        return $this;
    }

    public function maxNumberOfFiles($maxNumberOfFiles) {
        $this->maxNumberOfFiles = $maxNumberOfFiles;
        return $this;
    }

    public function maxFilesize($maxFilesize) {
        $this->maxFilesize = $maxFilesize;
        return $this;
    }

    public function accepts($accepts) {
        $this->accepts = $accepts;
        return $this;
    }

    public function canView($viewPermission) {
        $this->viewPermission = $viewPermission;
        return $this;
    }

    public function canUpload($uploadPermission) {
        $this->uploadPermission = $uploadPermission;
        return $this;
    }


    //getters?
    public function isImage() {
        return $this->is_image;
    }

    public function getDisk() {
        return $this->disk;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getName() {
        return $this->name;
    }

    public function getMaxNumberOfFiles() {
        return $this->maxNumberOfFiles;
    }

    public function getMaxFilesize() {
        return $this->maxFilesize;
    }

    public function getAcceptedFileTypes() {
        return $this->accepts;
    }


    public function getViewPermission() {
        return $this->viewPermission;
    }

    public function getUploadPermission() {
        return $this->uploadPermission;
    }
}