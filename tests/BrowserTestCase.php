<?php

namespace IsaEken\ThemeSystem\Tests;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use IsaEken\ThemeSystem\Http\Middlewares\ThemeMiddleware;
use IsaEken\ThemeSystem\ThemeSystem;
use IsaEken\ThemeSystem\ThemeSystemServiceProvider;
use Orchestra\Testbench\BrowserKit\TestCase as BaseTestCase;

abstract class BrowserTestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            ThemeSystemServiceProvider::class,
        ];
    }

    /**
     * @param  \Illuminate\Foundation\Application  $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $themeName = 'browser-test-theme';

        if (!theme_system()->isExists($themeName)) {
            theme_system()->make($themeName);
            File::put(theme_path($themeName) . "/testing.blade.php", 'Testing');
        }

        /** @var Router $router */
        $router = $app['router'];

        $router->get('/hello-world', function () {
            return 'Hello World';
        });

        $router->get('/middleware', function () {
            return view('testing');
        })->middleware(ThemeMiddleware::class . ":$themeName");
    }

    public $baseUrl = 'http://localhost';
}
