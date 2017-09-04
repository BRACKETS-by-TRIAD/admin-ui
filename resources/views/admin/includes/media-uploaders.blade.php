@foreach(app("App\Models\Movie")->getMediaCollections() as $collection)
	@if (isset($movie))
		@include('brackets/admin::admin.includes.media-uploader', ['collection' => $collection, 'media' => $model->getThumbsForCollection($collection->name)])
	@else
		@include('brackets/admin::admin.includes.media-uploader', ['collection' => $collection])
	@endif
@endforeach