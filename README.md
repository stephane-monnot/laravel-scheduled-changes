# Laravel scheduled changes

[![Latest Version on Packagist](https://img.shields.io/packagist/v/stephane-monnot/laravel-scheduled-changes.svg?style=flat-square)](https://packagist.org/packages/stephane-monnot/laravel-scheduled-changes)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/stephane-monnot/laravel-scheduled-changes/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/stephane-monnot/laravel-scheduled-changes/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/stephane-monnot/laravel-scheduled-changes/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/stephane-monnot/laravel-scheduled-changes/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/stephane-monnot/laravel-scheduled-changes.svg?style=flat-square)](https://packagist.org/packages/stephane-monnot/laravel-scheduled-changes)

A powerful and flexible package to schedule and execute future changes or events in your Laravel application. Manage tasks like content publication, data updates, unpublishing, and more with an easy-to-use interface and robust API support.

## Installation

You can install the package via composer:

```bash
composer require stephane-monnot/laravel-scheduled-changes
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="laravel-scheduled-changes-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-scheduled-changes-config"
```

This is the contents of the published config file:

```php
return [
    'models' => [
        'schedule_change' => StephaneMonnot\LaravelScheduledChanges\Models\ScheduleChange::class,
        'scheduled_unit' => StephaneMonnot\LaravelScheduledChanges\Models\ScheduledUnit::class,
    ],
    'table_names' => [
        'schedule_changes' => 'schedule_changes',
        'scheduled_units' => 'scheduled_units',
    ],
];
```

## Usage

```php
use StephaneMonnot\LaravelScheduledChanges\Models\ScheduleChange;
use StephaneMonnot\LaravelScheduledChanges\Models\ScheduledUnit;

// Create a article or something else
$article = Article::create([
    'title' => 'New article',
    'content' => 'This is a new article',
]);

// Create a new scheduled change
$scheduleChange = ScheduleChange::create([
    'name' => 'Publish new article',
    'description' => 'This article will be published in 2 days',
    'scheduled_at' => now()->addDays(2),
]);

// Create a new scheduled unit
$scheduleChange->units()->create([
    'data' => [
        'serialized_model' => serialize($article),
    ],
]);

Add to your scheduler the following command to execute the scheduled changes:
Schedule::command('scheduled-changes:process --dispatch')->everyMinute();
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Stéphane Monnot](https://github.com/stephane-monnot)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
