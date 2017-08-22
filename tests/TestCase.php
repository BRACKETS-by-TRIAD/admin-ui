<?php

namespace Brackets\Admin\Tests;

use Brackets\Admin\AdminProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Brackets\AdminTranslations\Test\Exceptions\Handler;
use Exception;
use Illuminate\Contracts\Debug\ExceptionHandler;

abstract class TestCase extends Orchestra
{
    /** @var \Brackets\AdminTranslations\Translation */
    protected $languageLine;

    public function setUp()
    {
        parent::setUp();
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            AdminProvider::class,
        ];
    }

    public function disableExceptionHandling()
    {
        $this->app->instance(ExceptionHandler::class, new class extends Handler {
            public function __construct() {}

            public function report(Exception $e)
            {
                // no-op
            }

            public function render($request, Exception $e) {
                throw $e;
            }
        });
    }
}
