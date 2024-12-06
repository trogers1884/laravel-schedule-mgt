<?php

namespace Trogers1884\LaravelScheduleMgt;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\App;
use Trogers1884\LaravelScheduleMgt\Support\ScheduleStorage;

class ScheduleManager
{
    private ScheduleStorage $storage;

    public function __construct(ScheduleStorage $storage)
    {
        $this->storage = $storage;
    }

    /**
     * Register all active scheduled tasks with Laravel's scheduler
     */
    public static function schedule(): void
    {
        $instance = app(self::class);
        $schedule = App::make(Schedule::class);

        $scheduledTasks = $instance->storage->all();

        foreach ($scheduledTasks as $task) {
            if (!($task['is_active'] ?? 0) || !empty($task['deleted_at'])) {
                continue;
            }

            // Create the base command
            $event = $schedule->command(
                $task['command'],
                $task['parameters'] ?? []
            );

            // Apply the main frequency
            call_user_func_array(
                [$event, $task['frequency_method']],
                $task['frequency_parameters'] ?? []
            );

            // Apply additional constraints
            if (!empty($task['constraints'])) {
                foreach ($task['constraints'] as $constraint) {
                    if (isset($constraint['method'], $constraint['parameters'])) {
                        call_user_func_array(
                            [$event, $constraint['method']],
                            $constraint['parameters']
                        );
                    }
                }
            }
        }
    }

    /**
     * Add a new scheduled task
     */
    public function addTask(
        string $command,
        string $frequencyMethod,
        array $frequencyParameters = [],
        array $commandParameters = [],
        array $constraints = [],
        bool $isActive = true
    ): int {
        return $this->storage->save([
            'command' => $command,
            'parameters' => $commandParameters,
            'frequency_method' => $frequencyMethod,
            'frequency_parameters' => $frequencyParameters,
            'constraints' => $constraints,
            'is_active' => $isActive ? 1 : 0,
        ]);
    }

    /**
     * Update an existing task
     */
    public function updateTask(int $id, array $data): bool
    {
        return $this->storage->update($id, $data);
    }

    /**
     * Delete a task
     */
    public function deleteTask(int $id): bool
    {
        return $this->storage->delete($id);
    }

    /**
     * Get all tasks
     */
    public function getAllTasks(): array
    {
        return $this->storage->all();
    }

    /**
     * Toggle task active status
     */
    public function toggleTask(int $id, bool $active): bool
    {
        return $this->storage->update($id, ['is_active' => $active ? 1 : 0]);
    }
}