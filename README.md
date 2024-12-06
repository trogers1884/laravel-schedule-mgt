# Laravel Schedule Management

A Laravel package for managing scheduled tasks through file storage, allowing dynamic configuration of Laravel's task scheduler without code changes.

## Features

- Manage scheduled tasks without modifying code
- Store task configurations in JSON files
- Toggle tasks on/off without deployment
- Add custom parameters and constraints to scheduled tasks
- Command-line interface for task management
- No database dependencies required

## Requirements

- PHP 8.1 or higher
- Laravel 10.0 or higher

## Installation

You can install the package via composer:

```bash
composer require trogers1884/laravel-schedule-mgt
```

The package will automatically register its service provider.

## Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --tag="schedule-mgt-config"
```

This will create a `config/schedule-mgt.php` file with the following contents:

```php
return [
    'storage_path' => storage_path('app/schedule-mgt'),
    'file_format' => 'json',
];
```

## Laravel Version-Specific Setup

### Laravel 11
Add to your `routes/console.php`:
```php
use Trogers1884\LaravelScheduleMgt\ScheduleManager;

ScheduleManager::schedule();
```

### Laravel 10
Add to your `app/Console/Kernel.php` in the `schedule` method:
```php
use Trogers1884\LaravelScheduleMgt\ScheduleManager;

protected function schedule(Schedule $schedule)
{
    ScheduleManager::schedule();
}
```

### Important Notes
- For Laravel 10 and above, ensure your `config/app.php` has the package service provider if it's not auto-discovered:
  ```php
  'providers' => [
      // ...
      Trogers1884\LaravelScheduleMgt\ScheduleMgtServiceProvider::class,
  ],
  ```
## Usage

### Managing Tasks via Console Commands

#### List All Tasks
```bash
php artisan schedule:list
```

#### Add a New Task
```bash
php artisan schedule:add "command:name" \
    --frequency=daily \
    --parameters='["--param1", "value1"]' \
    --freq-parameters='["21:00"]' \
    --constraints='[{"method":"environments", "parameters":["production"]}]'
```

Options:
- `--frequency`: The scheduling frequency (daily, hourly, weekly, etc.)
- `--parameters`: JSON array of command parameters
- `--freq-parameters`: JSON array of frequency method parameters
- `--constraints`: JSON array of additional scheduling constraints
- `--inactive`: Set the task as inactive initially

#### Toggle Task Status
```bash
php artisan schedule:toggle 1 --active=0
```

### Frequency Methods

You can use any of Laravel's schedule frequency methods:

- `hourly()`
- `daily()`
- `weekly()`
- `monthly()`
- `quarterly()`
- `yearly()`
- `timezone()`
- `at()`
- `dailyAt()`
- `twiceDaily()`
- `weeklyOn()`
- `monthly()`
- `monthlyOn()`
- `lastDayOfMonth()`
- `quarterly()`
- `yearly()`
- `cron()`
- `everyMinute()`
- `everyTwoMinutes()`
- `everyThreeMinutes()`
- `everyFourMinutes()`
- `everyFiveMinutes()`
- `everyTenMinutes()`
- `everyFifteenMinutes()`
- `everyThirtyMinutes()`

### Available Constraints

You can add any of Laravel's schedule constraints:

- `environments(['production'])`
- `evenInMaintenanceMode()`
- `withoutOverlapping()`
- `onOneServer()`
- `between('8:00', '17:00')`
- `unlessBetween('23:00', '4:00')`
- `when(closure)`
- `skip(closure)`

### Example Tasks

#### Basic Daily Task
```bash
php artisan schedule:add "cache:clear" --frequency=daily
```

#### Weekly Backup with Parameters
```bash
php artisan schedule:add "backup:run" \
    --frequency=weekly \
    --freq-parameters='["monday", "3:00"]' \
    --parameters='["--only-db"]' \
    --constraints='[{"method":"environments", "parameters":["production"]}]'
```

#### Hourly Task with Multiple Constraints
```bash
php artisan schedule:add "queue:work" \
    --frequency=hourly \
    --constraints='[
        {"method":"withoutOverlapping"},
        {"method":"environments", "parameters":["production"]},
        {"method":"evenInMaintenanceMode"}
    ]'
```

## Testing the Package

Run the test suite:

```bash
composer test
```

## Security

If you discover any security related issues, please email security@yourdomain.com instead of using the issue tracker.

## Credits

- [Your Name](https://github.com/yourusername)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.