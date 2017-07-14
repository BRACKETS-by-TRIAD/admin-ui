<?php

namespace Brackets\Admin\MediaLibrary\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Response;
use Storage;
use Spatie\MediaLibrary\Media as MediaModel;
use Exception;

class FileViewController extends Controller {

	public function __construct() {
        // First, check user's permissions - ability to perform this request
        if(config('simpleweb-medialibrary.authorizeView')) {
            $this->authorize('medialibrary.view');  
        }
    }

    public function view(Request $request) {
    	$this->validate($request, [
            'path'   => 'required|string'
        ]);

        list($fileId) = explode("/", $request->get('path'), 2);

        if($medium = app(MediaModel::class)->find($fileId)) {
            $model = app($medium->model_type);
            
            if($model->getFileViewProtection() == $model::$FILE_PROTECTION_PERMISSION) {
                $this->authorize(strtolower(str_replace("\\",".", $medium->model_type)).".file.view.permission");
            }
            elseif($model->getFileViewProtection() == $model::$FILE_PROTECTION_POLICY) {
                $this->authorize(strtolower(str_replace("\\",".", $medium->model_type)).".file.view.policy");
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

        abort(404);
    }
}
