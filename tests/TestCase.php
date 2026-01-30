<?php

namespace Shaunthegeek\LaravelLangDb\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Shaunthegeek\LaravelLangDb\LaravelLangDbServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelLangDbServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    protected function defineDatabaseMigrations()
    {
        $migration = include __DIR__.'/../database/migrations/create_languages_table.php.stub';
        $migration->up();
    }
}
