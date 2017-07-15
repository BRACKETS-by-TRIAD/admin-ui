<?php namespace Brackets\Admin\Tests;

use Illuminate\Database\Eloquent\Model;

class TestModel extends Model
{
    protected $table = 'test_models';
    protected $guarded = [];
    public $timestamps = false;
}