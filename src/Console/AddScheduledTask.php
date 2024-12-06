<?php

namespace Trogers1884\LaravelScheduleMgt\Console;

use Illuminate\Console\Command;
use Trogers1884\LaravelScheduleMgt\ScheduleManager;

class AddScheduledTask extends Command
{
    protected $signature = 'schedule:add 
                          {command : The artisan command to schedule}
                          {--frequency=daily : The frequency method (daily, hourly, etc.)}
                          {--parameters=[] : JSON string of command parameters}
                          {--freq-parameters=[] : JSON string of frequency parameters}
                          {--constraints=[] : JSON string of additional constraints}
                          {--inactive : Set the task as inactive}';

    protected $description = 'Add a new scheduled task';

    public function handle(ScheduleManager $manager)
{
    $id = $manager->addTask(
        $this->argument('command'),
        $this->option('frequency'),
        json_decode($this->option('freq-parameters'), true) ?? [],
        json_decode($this->option('parameters'), true) ?? [],
        json_decode($this->option('constraints'), true) ?? [],
        !$this->option('inactive')
    );

    $this->info("Task added with ID: $id");
}
}
