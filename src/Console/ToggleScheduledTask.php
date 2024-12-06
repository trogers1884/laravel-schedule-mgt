<?php

namespace Trogers1884\LaravelScheduleMgt\Console;

use Illuminate\Console\Command;
use Trogers1884\LaravelScheduleMgt\ScheduleManager;

class ToggleScheduledTask extends Command
{
    protected $signature = 'schedule:toggle {id} {--active=1 : Set to 1 for active, 0 for inactive}';
    protected $description = 'Toggle a scheduled task active status';

    public function handle(ScheduleManager $manager)
    {
        $id = (int) $this->argument('id');
        $active = (bool) (int) $this->option('active'); // Force conversion to int then bool

        $success = $manager->toggleTask($id, $active);

        if ($success) {
            $this->info('Task ' . ($active ? 'activated' : 'deactivated') . ' successfully');

            // Show current status
            $tasks = $manager->getAllTasks();
            $task = collect($tasks)->firstWhere('id', $id);
            if ($task) {
                $this->table(
                    ['ID', 'Command', 'Active'],
                    [[
                        $task['id'],
                        $task['command'],
                        $task['is_active'] ? 'Yes' : 'No'
                    ]]
                );
            }
        } else {
            $this->error('Task not found or could not be updated');
        }
    }
}