@foreach($collections as $mediaCollection)
	@if (isset($model))
		@include('brackets/admin-ui::admin.includes.media-uploader', ['mediaCollection' => $mediaCollection, 'media' => $model->getThumbsForCollection($mediaCollection->getName())])
	@else
		@include('brackets/admin-ui::admin.includes.media-uploader', ['mediaCollection' => $mediaCollection])
	@endif
@endforeach