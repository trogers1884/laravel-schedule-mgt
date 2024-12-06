<?php

namespace Trogers1884\LaravelScheduleMgt\Console;

use Illuminate\Console\Command;
use Trogers1884\LaravelScheduleMgt\ScheduleManager;

class RemoveScheduledTask extends Command
{
    protected $signature = 'schedule:remove {id : The ID of the task to remove}';
    protected $description = 'Remove a scheduled task completely';

    public function handle(ScheduleManager $manager)
    {
        $id = (int) $this->argument('id');

        // Show the task that will be removed
        $tasks = $manager->getAllTasks();
        $task = collect($tasks)->firstWhere('id', $id);

        if (!$task) {
            $this->error("Task #{$id} not found.");
            return 1;
        }

        $this->table(
            ['ID', 'Command', 'Frequency', 'Active'],
            [[
                $task['id'],
                $task['command'],
                $task['frequency_method'],
                $task['is_active'] ? 'Yes' : 'No'
            ]]
        );

        if ($this->confirm("Are you sure you want to remove this task? This cannot be undone.")) {
            $success = $manager->deleteTask($id);

            if ($success) {
                $this->info('Task removed successfully');
                return 0;
            } else {
                $this->error('Failed to remove task');
                return 1;
            }
        }

        $this->info('Operation cancelled');
        return 0;
    }
}