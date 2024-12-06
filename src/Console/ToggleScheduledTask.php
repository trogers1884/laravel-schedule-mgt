<?php

namespace Trogers1884\LaravelScheduleMgt\Console;

use Illuminate\Console\Command;
use Trogers1884\LaravelScheduleMgt\ScheduleManager;

class ToggleScheduledTask extends Command
{
    protected $signature = 'schedule:toggle {id} {--active=1}';
    protected $description = 'Toggle a scheduled task active status';

    public function handle(ScheduleManager $manager)
    {
        $success = $manager->toggleTask(
            (int) $this->argument('id'),
            (bool) $this->option('active')
        );

        if ($success) {
            $this->info('Task status updated successfully');
        } else {
            $this->error('Task not found');
        }
    }
}
