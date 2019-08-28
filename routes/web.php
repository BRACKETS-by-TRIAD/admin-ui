<?php

Route::middleware(['web', 'admin'])->group(static function () {
    Route::namespace('Brackets\AdminUI\Http\Controllers')->group(static function () {
        Route::post('/admin/wysiwyg-media', 'WysiwygMediaUploadController@upload')->name('brackets/admin-ui::wysiwyg-upload');
    });
});
