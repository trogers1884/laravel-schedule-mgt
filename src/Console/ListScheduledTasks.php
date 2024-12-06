<?php

namespace Trogers1884\LaravelScheduleMgt\Console;

use Illuminate\Console\Command;
use Trogers1884\LaravelScheduleMgt\ScheduleManager;

class ListScheduledTasks extends Command
{
    protected $signature = 'schedule:list';
    protected $description = 'List all scheduled tasks';

    public function handle(ScheduleManager $manager)
    {
        $tasks = $manager->getAllTasks();

        $rows = collect($tasks)->map(function ($task) {
            return [
                $task['id'],
                $task['command'],
                $task['frequency_method'],
                json_encode($task['frequency_parameters']),
                $task['is_active'] ? 'Yes' : 'No',
                $task['created_at'],
                $task['updated_at'],
            ];
        })->toArray();

        $this->table(
            ['ID', 'Command', 'Frequency', 'Parameters', 'Active', 'Created', 'Updated'],
            $rows
        );
    }
}
