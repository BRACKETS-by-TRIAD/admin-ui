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

    /** @var \Brackets\Admin\MediaLibrary\Test\TestModel */
    protected $testUnsavedModel;

    /** @var \Brackets\Admin\MediaLibrary\Test\TestModelWithCollections */
    protected $TestModelWithCollections;

    /** @var \Brackets\Admin\MediaLibrary\Test\TestModelWithoutMediaConversions */
    protected $testModelWithoutMediaConversions;

    /** @var \Brackets\Admin\MediaLibrary\Test\TestModelWithMorphMap */
    protected $testModelWithMorphMap;

    public function setUp()
    {
        parent::setUp();

        $this->setUpDatabase($this->app);

        $this->setUpTempTestFiles();

        $this->testModel = TestModel::first();
        $this->testUnsavedModel = new TestModel;
        $this->TestModelWithCollections = TestModelWithCollections::first();
        $this->testModelWithoutMediaConversions = TestModelWithoutMediaConversions::first();
        $this->testModelWithMorphMap = TestModelWithMorphMap::first();
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

        $app['config']->set('filesystems.disks.media', [
            'driver' => 'local',
            'root' => $this->getMediaDirectory(),
        ]);

        $app['config']->set('filesystems.disks.secondMediaDisk', [
            'driver' => 'local',
            'root' => $this->getTempDirectory('media2'),
        ]);

        $app->bind('path.public', function () {
            return $this->getTempDirectory();
        });

        $app['config']->set('app.key', '6rE9Nz59bGRbeMATftriyQjrpF7DcOQm');

        $this->setupS3($app);
        $this->setUpMorphMap();
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
        return $this->getTempDirectory().'/testfiles'.($suffix == '' ? '' : '/'.$suffix);
    }

    public function getTestJpg()
    {
        return $this->getTestFilesDirectory('test.jpg');
    }

    public function getTestPng()
    {
        return $this->getTestFilesDirectory('test.png');
    }

    public function getTestWebm()
    {
        return $this->getTestFilesDirectory('test.webm');
    }

    public function getTestPdf()
    {
        return $this->getTestFilesDirectory('test.pdf');
    }

    public function getTestSvg()
    {
        return $this->getTestFilesDirectory('test.svg');
    }

    private function setUpMorphMap()
    {
        Relation::morphMap([
            'test-model-with-morph-map' => TestModelWithMorphMap::class,
        ]);
    }

    private function setupS3($app)
    {
        $s3Configuration = [
            'driver' => 's3',
            'key' => getenv('S3_ACCESS_KEY_ID'),
            'secret' => getenv('S3_SECRET_ACCESS_KEY'),
            'region' => getenv('S3_BUCKET_REGION'),
            'bucket' => getenv('S3_BUCKET_NAME'),
        ];

        $app['config']->set('filesystems.disks.s3', $s3Configuration);
        $app['config']->set(
            'medialibrary.s3.domain',
            'https://'.$s3Configuration['bucket'].'.s3.amazonaws.com'
        );
    }
}