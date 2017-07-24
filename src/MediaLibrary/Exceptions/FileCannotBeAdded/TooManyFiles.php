<?php

namespace Brackets\Admin\MediaLibrary\Exceptions\FileCannotBeAdded;

use Spatie\MediaLibrary\Helpers\File;
use Spatie\MediaLibrary\Exceptions\FileCannotBeAdded;

class TooManyFiles extends FileCannotBeAdded
{
    public static function create($fileCount, $maxFileCount, $collectionName)
    {

        return new static("Max file count in {$collectionName} is {$maxFileCount}");
    }
}