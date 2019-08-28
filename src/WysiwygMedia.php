<?php

namespace Brackets\AdminUI;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\File;

class WysiwygMedia extends Model
{
    protected $fillable = ['file_path'];

    /**
     * Boot with event handlers
     */
    protected static function boot(): void
    {
        parent::boot();

        static::deleted(static function ($model) {
            File::delete(public_path() . '/' . $model->file_path);
        });
    }

    /**
     * @return MorphTo
     */
    public function wysiwygable(): MorphTo
    {
        return $this->morphTo();
    }
}
