<?php

namespace Brackets\AdminUI\Traits;

use Brackets\AdminUI\WysiwygMedia;

trait HasWysiwygMediaTrait
{
    /**
     * Boot with event handlers
     */
    public static function bootHasWysiwygMediaTrait(): void
    {
        static::saved(static function ($model) {
            $wysiwygMediaIds = collect(request('wysiwygMedia'))->filter(static function ($wysiwygId) {
                return is_int($wysiwygId);
            });
            if ($wysiwygMediaIds->isNotEmpty()) {
                WysiwygMedia::whereIn('id', $wysiwygMediaIds)->get()->each(static function ($item) use ($model) {
                    $model->wysiwygMedia()->save($item);
                });
            }
        });

        static::deleted(static function ($model) {
            $model->wysiwygMedia->each(static function ($item) {
                $item->delete();
            });
        });
    }

    /**
     * @return mixed
     */
    public function wysiwygMedia()
    {
        return $this->morphMany(WysiwygMedia::class, 'wysiwygable');
    }
}
