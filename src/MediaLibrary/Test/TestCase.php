<?php

namespace Brackets\Admin\MediaLibrary\Test;

use File;
use Illuminate\Database\Schema\Blueprint;
use Orchestra\Testbench\TestCase as Orchestra;
use Illuminate\Database\Eloquent\Relations\Relation;

abstract class TestCase extends Orchestra
{
    /** @var \Brackets\Admin\MediaLibrary\Test\TestModel */
    protected $testModel;

    /** @var \Brackets\Admin\MediaLibrary\Test\TestModelWithCollections */
    protected $testModelWithCollections;

    public function setUp()
    {
        parent::setUp();

        $this->setUpDatabase($this->app);
        $this->setUpTempTestFiles();

        $this->testModel = TestModel::first();
        $this->testModelWithCollections = TestModelWithCollections::first();
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            \Spatie\MediaLibrary\MediaLibraryServiceProvider::class,
            \Brackets\Admin\AdminProvider::class
        ];
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $this->initializeDirectory($this->getTempDirectory());

        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        // $app['config']->set('filesystems.disks.media', [
        //     'driver' => 'local',
        //     'root' => $this->getMediaDirectory(),
        // ]);

        // $app['config']->set('filesystems.disks.secondMediaDisk', [
        //     'driver' => 'local',
        //     'root' => $this->getTempDirectory('media2'),
        // ]);


        $app['config']->set('filesystems.disks.media', [
            'driver' => 'local',
            // 'root' => public_path().'/media',
            'root' => $this->getMediaDirectory(),
        ]);

        $app['config']->set('filesystems.disks.media-protected', [
            'driver' => 'local',
            // 'root' => storage_path().'/app/media',
             'root' => $this->getMediaDirectory('storage'),
        ]);

        $app['config']->set('simpleweb-medialibrary', [
            'default_public_disk' => 'media',
            'default_protected_disk' => 'media-protected'
        ]);

        $app['config']->set('medialibrary.custom_url_generator_class', \Brackets\Admin\MediaLibrary\LocalUrlGenerator::class);

        $app->bind('path.public', function () {
            return $this->getTempDirectory();
        });

        $app->bind('path.storage', function () {
            return $this->getTempDirectory();
        });

        $app['config']->set('app.key', '6rE9Nz59bGRbeMATftriyQjrpF7DcOQm');
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function setUpDatabase($app)
    {
        $app['db']->connection()->getSchemaBuilder()->create('test_models', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('width')->nullable();
        });

        TestModel::create(['name' => 'test']);

        include_once 'vendor/spatie/laravel-medialibrary/database/migrations/create_media_table.php.stub';

        (new \CreateMediaTable())->up();
    }

    protected function setUpTempTestFiles()
    {
        $this->initializeDirectory($this->getTestFilesDirectory());
        File::copyDirectory(__DIR__.'/testfiles', $this->getTestFilesDirectory());
    }

    protected function initializeDirectory($directory)
    {
        if (File::isDirectory($directory)) {
            File::deleteDirectory($directory);
        }
        File::makeDirectory($directory);
    }

    public function getTempDirectory($suffix = '')
    {
        return __DIR__.'/temp'.($suffix == '' ? '' : '/'.$suffix);
    }

    public function getMediaDirectory($suffix = '')
    {
        return $this->getTempDirectory().'/media'.($suffix == '' ? '' : '/'.$suffix);
    }

    public function getTestFilesDirectory($suffix = '')
    {
        return $this->getTempDirectory().'/app'.($suffix == '' ? '' : '/'.$suffix);
    }
}