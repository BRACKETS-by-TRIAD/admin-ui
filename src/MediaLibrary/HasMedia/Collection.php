<?php

namespace Brackets\Admin\MediaLibrary\HasMedia;

class Collection  {

    protected $is_image = false;
    protected $name;
    protected $disk;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->disk = config('medialibrary.default_public_disk', 'media');
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
        $this->disk = config('medialibrary.default_protected_disk');
        return $this;
    }

    // TODO

}