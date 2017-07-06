<?php

Route::group(['namespace' => 'Brackets\Admin\MediaLibrary\Http\Controllers'], function(){
    Route::post('upload',    ['as' => 'mediaLibrary.upload',         'uses' => 'FileUploadController@upload']);
    Route::get('view',    	 ['as' => 'mediaLibrary.view',           'uses' => 'FileViewController@view']);
    // Route::any('wysiwyg/dragdrop',  ['as' => 'mediaLibrary.wysiwyg.dragdrop', 'uses' => 'Upload\UploadController@wysiwygDragDropUpload']);
    // Route::any('wysiwyg/upload',    ['as' => 'mediaLibrary.wysiwyg.upload',   'uses' => 'Upload\UploadController@wysiwygImageUpload']);
});