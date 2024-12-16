<?php

namespace StephaneMonnot\LaravelScheduledChanges\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use StephaneMonnot\LaravelScheduledChanges\LaravelScheduledChangesServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom([
            '--database' => 'testing',
            '--path' => realpath(__DIR__.'/TestMigrations'),
        ]);
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelScheduledChangesServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        $migration = include __DIR__.'/../database/migrations/create_scheduled_changes_table.php.stub';
        $migration->up();
    }
}
