<?php

namespace IsaEken\ThemeSystem\Tests;

use IsaEken\ThemeSystem\ThemeSystemServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            ThemeSystemServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
    }
}
