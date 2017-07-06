<?php

namespace Brackets\Admin\MediaLibrary\Http\Controllers;

use App\Http\Controllers\Controller;
use Brackets\Simpleweb\Http\Middleware\Admin;
use Imageupload;
use Illuminate\Http\Request;

class FileUploadController extends Controller {

    // protected $wysiwygUploadPath;

    public function __construct() {
        if(config('simpleweb-medialibrary.authorizeUpload')) {
            $this->authorize('medialibrary.upload');  
        }

        // $this->wysiwygUploadPath = '/uploads/wysiwyg';
    }

    //FIXME: potrebujeme tu Imageupload ?
    public function upload(Request $request) {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $temp = explode('.', $file->getClientOriginalName());
            $ext  = array_pop($temp);
            $name = implode('.', $temp);
            $newFileName = $name.'-'.time();
            $fileInfo = Imageupload::upload($request->file('file'), $newFileName);

            return ['success' => true, 'data' => $fileInfo];
        }

        return ['success' => false, 'error' => 'File is not provided'];
    }

    // public function wysiwygDragDropUpload(Request $request) {
    //     if (!$request->hasFile('upload')) {
    //         return array("uploaded" => 0, "error" => array("message" => "Failed to upload the file - file not sent"));
    //     }
    //     if (!$request->file('upload')->isValid()) {
    //         return array("uploaded" => 0, "error" => array("message" => "Failed to upload the file - file transfer failed"));
    //     }

    //     $destinationPath = public_path() . $this->wysiwygUploadPath;

    //     /**
    //      * @var \Symfony\Component\HttpFoundation\File\UploadedFile
    //      */
    //     $file = $request->file('upload');
    //     if(!$request->file('upload')->move($destinationPath, $file->getClientOriginalName())) {
    //         return array("uploaded" => 0, "error" => array("message" => "Failed to upload the file - could not move file"));
    //     }

    //     return array("uploaded" => 1, "fileName" => $file->getClientOriginalName(), "url" => $this->wysiwygUploadPath . "/" . $file->getClientOriginalName());
    // }

    // public function wysiwygImageUpload(Request $request) {
    //     $funcNum = $request->get("CKEditorFuncNum");
    //     $url = '';
    //     $message = '';
    //     $destinationPath = public_path() . $this->wysiwygUploadPath;

    //     if (!$request->hasFile('upload')) {
    //         $message = 'Failed to uplad - file not sent';
    //     }
    //     if (!$request->file('upload')->isValid()) {
    //         $message = 'Failed to uplad - file transfer failed';
    //     }

    //     $file = $request->file('upload');
    //     if($request->file('upload')->move($destinationPath, $file->getClientOriginalName())) {
    //         $url = $this->wysiwygUploadPath . "/" . $file->getClientOriginalName();
    //     }

    //     echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum, '$url', '$message');</script>";
    // }

}
