<?php

namespace Brackets\Admin\MediaLibrary;

use Spatie\MediaLibrary\UrlGenerator\LocalUrlGenerator as SpatieLocalUrlGenerator;

class LocalUrlGenerator extends SpatieLocalUrlGenerator {

    public function getUrl(): string {
        if($this->media->disk == 'media-protected') {
            $url = $this->getPathRelativeToRoot();
            return route('mediaLibrary.view', [], false) . '?path=' . $this->makeCompatibleForNonUnixHosts($url);
        } else {
            $url = $this->getBaseMediaDirectory().'/'.$this->getPathRelativeToRoot();
            return $this->makeCompatibleForNonUnixHosts($url);
        }
    }
}
