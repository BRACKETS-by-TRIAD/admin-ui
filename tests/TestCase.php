<?php namespace Brackets\Admin\Tests;

use Brackets\Admin\Test\TestModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Orchestra\Testbench\TestCase as Test;

abstract class TestCase extends Test
{

    /**
     * @var Model
     */
    protected $testModel;

    public function setUp()
    {
        parent::setUp();
        $this->setUpDatabase($this->app);
        $this->testModel = TestModel::first();
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function setUpDatabase($app)
    {
        $app['db']->connection()->getSchemaBuilder()->create('test_models', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('color');
            $table->integer('number');
            $table->dateTime('published_at');
        });

        TestModel::create([
            'name' => 'Alpha',
            'color' => 'red',
            'number' => 999,
            'published_at' => '2017-01-01 00:00:00',
        ]);

        collect(range(2, 10))->each(function($i){
            TestModel::create([
                'name' => 'Zeta '.$i,
                'color' => 'yellow',
                'number' => $i,
                'published_at' => (2017+$i).'-01-01 00:00:00',
            ]);
        });

        print_r(TestModel::all()->toArray());
    }

}
