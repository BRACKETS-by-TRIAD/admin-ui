<media-upload
        ref="{{ $mediaCollection->getName() }}_uploader"
        :collection="'{{ $mediaCollection->getName() }}'"
        :url="'{{ route('brackets/media::upload') }}'"
        @if($mediaCollection->getMaxNumberOfFiles())
        :max-number-of-files="{{ $mediaCollection->getMaxNumberOfFiles() }}"
        @endif
        @if($mediaCollection->getMaxFileSize())
        :max-file-size-in-mb="{{ round(($mediaCollection->getMaxFileSize()/1024/1024), 2) }}"
        @endif
        @if($mediaCollection->getAcceptedFileTypes())
        :accepted-file-types="'{{ implode(',', $mediaCollection->getAcceptedFileTypes()) }}'"
        @endif
        @if(isset($media) && $media->count() > 0)
        :uploaded-images="{{ $media->toJson() }}"
        @endif
></media-upload>