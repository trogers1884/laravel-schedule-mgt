<?php

namespace Trogers1884\LaravelScheduleMgt\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Trogers1884\LaravelScheduleMgt\ScheduleMgtServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            ScheduleMgtServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }
}