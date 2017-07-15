<?php namespace Brackets\Admin\Tests;

use Illuminate\Database\Eloquent\Model;
use Dimsav\Translatable\Translatable;

class TestTranslatableModel extends Model
{
    use Translatable;

    protected $table = 'test_translatable_models';
    protected $guarded = [];
    public $timestamps = false;

    // these attributes are loaded from translation model/table
    public $translatedAttributes = [
        'name',
        'color',
    ];
}