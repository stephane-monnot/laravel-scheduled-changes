<?php

namespace StephaneMonnot\LaravelScheduledChanges;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use StephaneMonnot\LaravelScheduledChanges\Commands\ProcessScheduledChangesCommand;

class LaravelScheduledChangesServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-scheduled-changes')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_scheduled_changes_table')
            ->hasCommand(ProcessScheduledChangesCommand::class);
    }
}
