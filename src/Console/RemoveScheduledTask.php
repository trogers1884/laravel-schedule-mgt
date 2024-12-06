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
        if ($this->confirm("Are you sure you want to remove task #{$this->argument('id')}? This cannot be undone.")) {
            $success = $manager->deleteTask((int) $this->argument('id'));

            if ($success) {
                $this->info('Task removed successfully');
            } else {
                $this->error('Task not found or could not be removed');
            }
        }
    }
}
