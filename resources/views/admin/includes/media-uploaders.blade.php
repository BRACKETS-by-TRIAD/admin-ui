@foreach($collections as $collection)
	@if (isset($model))
		@include('brackets/admin::admin.includes.media-uploader', ['collection' => $collection, 'media' => $model->getThumbsForCollection($collection->name)])
	@else
		@include('brackets/admin::admin.includes.media-uploader', ['collection' => $collection])
	@endif
@endforeach