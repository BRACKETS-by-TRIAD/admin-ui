<?php

namespace Brackets\Admin\MediaLibrary\HasMedia;

class Collection  {

    protected $is_image = false;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public static function create(string $name)
    {
        return new static($name);
    }

    public function title($title) {
        $this->title = $title;
        return $this;
    }

    public function image() {
        $this->is_image = true;
        return $this;
    }

}