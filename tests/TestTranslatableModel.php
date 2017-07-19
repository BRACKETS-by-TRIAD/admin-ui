<?php namespace Brackets\Admin\Tests;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class TestTranslatableModel extends Model
{
    use HasTranslations;

    protected $table = 'test_translatable_models';
    protected $guarded = [];
    public $timestamps = false;

    // these attributes are translated
    public $translatable = [
        'name',
        'color',
    ];
}