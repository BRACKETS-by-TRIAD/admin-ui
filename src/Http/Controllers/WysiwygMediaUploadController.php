<?php

namespace Brackets\AdminUI\Http\Controllers;

use Brackets\AdminUI\WysiwygMedia;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Config;
use Intervention\Image\Facades\Image;
use Spatie\ImageOptimizer\OptimizerChainFactory;
use Illuminate\Support\Facades\File;

class WysiwygMediaUploadController extends BaseController {

    public function upload(Request $request)
    {
        // get image from request and check validity
        $temporaryFile = $request->file('fileToUpload');
        if (!$temporaryFile->isFile() || !in_array($temporaryFile->getMimeType(), ['image/png', 'image/jpeg', 'image/gif', 'image/svg+xml'])) {
            return response()->json([
                'success' => false
            ]);
        }

        // generate path that it will be saved to
        $savedPath = Config::get('wysiwyg-media.media_folder') . '/' . time() . $temporaryFile->getClientOriginalName();

        // create directory in which we will be uploading into
        if (!File::isDirectory(Config::get('wysiwyg-media.media_folder'))) {
            File::makeDirectory(Config::get('wysiwyg-media.media_folder'), 0755, true);
        }
      
        // resize and save image
        Image::make($temporaryFile->path())
            ->resize(Config::get('wysiwyg-media.maximum_image_width'), null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->save($savedPath);

        // optimize image
        OptimizerChainFactory::create()->optimize($savedPath);

        // create related model
        $wysiwygMedia = WysiwygMedia::create(['file_path' => $savedPath]);

        // return image's path to use in wysiwyg
        return response()->json([
            'file' => url($savedPath),
            'mediaId' => $wysiwygMedia->id,
            'success' => true
        ]);
    }
}
