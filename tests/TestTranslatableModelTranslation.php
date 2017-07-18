<?php namespace Brackets\Admin\Tests;

use Illuminate\Database\Eloquent\Model;

class TestTranslatableModelTranslation extends Model
{
    protected $table = 'test_translatable_model_translations';
    protected $guarded = [];
    public $timestamps = false;
}