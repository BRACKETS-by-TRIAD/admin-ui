<?php namespace Brackets\Admin\Tests;

use Brackets\Admin\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class TestTranslatableModel extends Model
{
    use HasTranslations;

    protected $table = 'test_translatable_models';
    protected $guarded = [];
    public $timestamps = false;

    // these attributes are translatable
    public $translatable = [
        'name',
        'color',
    ];
}