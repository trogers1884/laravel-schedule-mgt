<?php

namespace Trogers1884\LaravelScheduleMgt;

use Illuminate\Support\ServiceProvider;
use Trogers1884\LaravelScheduleMgt\Console\{
    ListScheduledTasks,
    AddScheduledTask,
    ToggleScheduledTask,
    RemoveScheduledTask
};

class ScheduleMgtServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/schedule-mgt.php', 'schedule-mgt'
        );

        $this->app->singleton(ScheduleManager::class, function ($app) {
            return new ScheduleManager(
                $app->make(Support\ScheduleStorage::class)
            );
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/schedule-mgt.php' => config_path('schedule-mgt.php'),
        ], 'schedule-mgt-config');

        if ($this->app->runningInConsole()) {
            $this->commands([
                ListScheduledTasks::class,
                AddScheduledTask::class,
                ToggleScheduledTask::class,
                RemoveScheduledTask::class,
            ]);
        }
    }
}