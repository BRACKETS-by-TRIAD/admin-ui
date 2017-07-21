<?php

namespace Brackets\Admin\MediaLibrary\Exceptions\FileCannotBeAdded;

use Spatie\MediaLibrary\Helpers\File;
use Spatie\MediaLibrary\Exceptions\FileCannotBeAdded;

class TooManyFiles extends FileCannotBeAdded
{
    public static function create($files, $maxFileCount)
    {
        $actualFilesCount = 15;

        return new static("File size is {$actualFilesCount}, while max size of file is {$maxFileCount}");
    }
}