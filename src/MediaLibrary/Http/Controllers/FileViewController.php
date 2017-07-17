<?php

namespace Brackets\Admin\MediaLibrary\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Response;
use Storage;
use Spatie\MediaLibrary\Media as MediaModel;
use Exception;

use Illuminate\Support\Facades\Gate;

class FileViewController extends Controller {

	public function __construct() {
        // First, check user's permissions - ability to perform this request
        // if(config('simpleweb-medialibrary.authorizeView')) {
        //     $this->authorize('medialibrary.view');  
        // }
    }

    public function view(Request $request) {
    	$this->validate($request, [
            'path'   => 'required|string'
        ]);

        list($fileId) = explode("/", $request->get('path'), 2);

        if($medium = app(MediaModel::class)->find($fileId)) {
            $mediaCollections = $medium->model->getMediaCollections();

            $collection = $mediaCollections->filter(function($collection) use ($medium){
                return $collection->name == $medium->collection_name;
            })->first();

            if($collection) {
                if($collection->viewPermission) {
                    $this->authorize($collection->viewPermission, $medium->model);
                }

                $storagePath = '/media/'.$request->get('path');

                if(!Storage::has($storagePath)) {
                    abort(404);
                }

                $fileOnDisc  = Storage::get($storagePath);

                return Response::make($fileOnDisc, 200, [
                    'Content-Type' => Storage::mimeType($storagePath),
                    'Content-Disposition' => 'inline; filename="'.basename($request->get('path')).'"'
                ]);
            }
        }

        abort(404);
    }
}
