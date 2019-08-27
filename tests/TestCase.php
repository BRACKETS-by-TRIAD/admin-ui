<?php

namespace Brackets\AdminUI\Tests;

use Brackets\AdminTranslations\Test\Exceptions\Handler;
use Brackets\AdminUI\AdminUIServiceProvider;
use Exception;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Support\Facades\File;
use Orchestra\Testbench\BrowserKit\TestCase as OrchestraBrowser;

abstract class TestCase extends OrchestraBrowser
{
    /** @var \Brackets\AdminTranslations\Translation */
    protected $languageLine;

    public function setUp(): void
    {
        parent::setUp();

        // let's define simple routes
        $this->app['router']->get('/admin/test/index', function () {
            return view('admin.test.index');
        });

        File::copyDirectory(__DIR__.'/fixtures/public', public_path());
        File::copyDirectory(__DIR__.'/fixtures/resources/views', resource_path('views'));
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            AdminUIServiceProvider::class,
        ];
    }

    public function disableExceptionHandling()
    {
        $this->app->instance(ExceptionHandler::class, new class extends Handler {
            public function __construct()
            {
            }

            public function report(Exception $e)
            {
                // no-op
            }

            public function render($request, Exception $e)
            {
                throw $e;
            }
        });
    }
}
