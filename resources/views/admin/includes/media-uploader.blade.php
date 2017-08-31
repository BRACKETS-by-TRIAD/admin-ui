<div class="card-header">
	@if($collection->isImage())
		<i class="fa fa-file-image-o"></i>
	@else
		<i class="fa fa-file-o"></i>
	@endif

	{{ $collection->title }}
	@if($collection->maxNumberOfFiles)
		<small>(max no. of files: {{ $collection->maxNumberOfFiles }} files)</small>
	@endif
	@if($collection->maxFilesize)
		<small>(max size per file: {{ number_format($collection->maxFilesize/1024/1024, 2)  }} MB)</small>
	@endif

	@if($collection->isPrivate())
		<a class="pull-right" data-toggle="tooltip" data-placement="top" title="Files are not accesible for public"> <i class="fa fa-lock" aria-hidden="true"></i></a>
	@endif
</div>

<{{ strtolower($modelName) }}-upload
	ref="{{ $collection->name }}_uploader"
	:collection="'{{ $collection->name }}'"
	:url="'{{ route('brackets/media:upload') }}'"
	@if($collection->maxNumberOfFiles)
		:max-number-of-files="{{ $collection->maxNumberOfFiles }}"
	@endif
	@if($collection->maxFilesize)
		:max-file-size-in-mb="{{ round($collection->maxFilesize/1024/1024) }}"
	@endif
	@if($collection->acceptedFileTypes)
		:accepted-file-types="'{{ $collection->acceptedFileTypes }}'"
	@endif
	@if($model->getThumbsForCollection($collection->name)->count() > 0)
		:uploaded-images="{{ $model->getThumbsForCollection($collection->name)->toJson() }}"
	@endif
/>