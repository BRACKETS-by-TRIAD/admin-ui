<?php

namespace Brackets\Admin\MediaLibrary\Exceptions\FileCannotBeAdded;

use Spatie\MediaLibrary\Helpers\File;
use Spatie\MediaLibrary\Exceptions\FileCannotBeAdded;

class FileIsTooBig extends FileCannotBeAdded
{
    public static function create($file, $maxSize)
    {
        $actualFileSize = filesize($file);

        return new static("File size is {$actualFileSize}, while max size of file is {$maxSize}");
    }
}