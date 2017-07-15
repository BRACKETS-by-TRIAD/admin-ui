<?php namespace Brackets\Admin\Tests;

use Brackets\Admin\AdminListing;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Orchestra\Testbench\TestCase as Test;

abstract class TestCase extends Test
{

    /**
     * @var Model
     */
    protected $testModel;

    /**
     * @var AdminListing
     */
    protected $listing;

    /**
     * @var AdminListing
     */
    protected $translatedListing;

    public function setUp()
    {
        parent::setUp();
        $this->setUpDatabase($this->app);
        $this->testModel = TestModel::first();
        $this->listing = AdminListing::create(TestModel::class);
        $this->translatedListing = AdminListing::create(TestTranslatableModel::class);
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
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
            'published_at' => '2000-06-01 00:00:00',
        ]);

        collect(range(2, 10))->each(function($i){
            TestModel::create([
                'name' => 'Zeta '.$i,
                'color' => 'yellow',
                'number' => $i,
                'published_at' => (1998+$i).'-01-01 00:00:00',
            ]);
        });

        app()->make('config')->set('translatable.locales', ['en', 'sk']);

        $app['db']->connection()->getSchemaBuilder()->create('test_translatable_models', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('number');
            $table->dateTime('published_at');
        });
        $app['db']->connection()->getSchemaBuilder()->create('test_translatable_model_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('test_translatable_model_id')->unsigned();
            $table->string('locale')->index();

            $table->string('name');
            $table->string('color');

            $table->unique(['test_translatable_model_id','locale']);
            $table->foreign('test_translatable_model_id')->references('id')->on('test_translatable_models')->onDelete('cascade');
        });

        TestTranslatableModel::create([
            'en' => [
                'name' => 'Alpha',
                'color' => 'red',
            ],
            'sk' => [
                'name' => 'Alfa',
                'color' => 'cervena',
            ],
            'number' => 999,
            'published_at' => '2000-06-01 00:00:00',
        ]);

        collect(range(2, 10))->each(function($i){
            TestTranslatableModel::create([
                'en' => [
                    'name' => 'Zeta '.$i,
                    'color' => 'yellow',
                ],
                'number' => $i,
                'published_at' => (1998+$i).'-01-01 00:00:00',
            ]);
        });
    }

}
