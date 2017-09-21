<div class="card-header">
	@if($mediaCollection->isImage())
		<i class="fa fa-file-image-o"></i>
	@else
		<i class="fa fa-file-o"></i>
	@endif

	{{-- How to work around this? We don't want that MediaCollection know about the form name. Maybe we can use trans() and our predefined translations path? --}}
	{{-- $mediaCollection->getTitle() --}}
	@if($mediaCollection->getMaxNumberOfFiles())
		<small>{{ trans('brackets/admin-ui::admin.media_uploader.max_number_of_files', ['maxNumberOfFiles' => $mediaCollection->getMaxNumberOfFiles()]) }}</small>
	@endif
	@if($mediaCollection->getMaxFileSize())
		<small>{{ trans('brackets/admin-ui::admin.media_uploader.max_size_pre_file', ['maxFileSize' => number_format($mediaCollection->getMaxFileSize()/1024/1024, 2)]) }}</small>
	@endif

	@if($mediaCollection->isPrivate())
		<a class="pull-right" data-toggle="tooltip" data-placement="top" title="{{ trans('brackets/admin-ui::admin.media_uploader.private_title') }}"> <i class="fa fa-lock" aria-hidden="true"></i></a>
	@endif
</div>

<media-upload
		ref="{{ $mediaCollection->getName() }}_uploader"
		:collection="'{{ $mediaCollection->getName() }}'"
		:url="'{{ route('brackets/media::upload') }}'"
		@if($mediaCollection->getMaxNumberOfFiles())
		:max-number-of-files="{{ $mediaCollection->getMaxNumberOfFiles() }}"
		@endif
		@if($mediaCollection->getMaxFileSize())
		:max-file-size-in-mb="{{ round($mediaCollection->getMaxFileSize()/1024/1024) }}"
		@endif
		@if($mediaCollection->getAcceptedFileTypes())
		:accepted-file-types="'{{ $mediaCollection->getAcceptedFileTypes() }}'"
		@endif
		@if(isset($media))
		@if($media->getThumbsForCollection($mediaCollection->getName())->count() > 0)
		:uploaded-images="{{ $media->getThumbsForCollection($mediaCollection->getName())->toJson() }}"
		@endif
		@endif
></media-upload>