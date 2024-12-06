<?php

namespace Trogers1884\LaravelScheduleMgt\Support;

use Illuminate\Support\Facades\File;
use InvalidArgumentException;

class ScheduleStorage
{
    private string $storagePath;
    private string $storageFile;

    public function __construct()
    {
        $this->storagePath = config('schedule-mgt.storage_path', storage_path('app/schedule-mgt'));
        $this->storageFile = $this->storagePath . '/scheduled_tasks.json';

        if (!File::exists($this->storagePath)) {
            File::makeDirectory($this->storagePath, 0755, true);
        }

        if (!File::exists($this->storageFile)) {
            File::put($this->storageFile, json_encode([]));
        }
    }

    public function all(): array
    {
        $tasks = json_decode(File::get($this->storageFile), true) ?? [];
        // Filter out deleted tasks
        return array_values(array_filter($tasks, fn($task) => empty($task['deleted_at'])));
    }

    public function save(array $task): int
    {
        $tasks = json_decode(File::get($this->storageFile), true) ?? [];

        // Validate required fields
        $requiredFields = ['command', 'frequency_method'];
        foreach ($requiredFields as $field) {
            if (empty($task[$field])) {
                throw new InvalidArgumentException("The {$field} field is required");
            }
        }

        // Add metadata
        $task['id'] = $this->getNextId($tasks);
        $task['created_at'] = now()->toISOString();
        $task['updated_at'] = now()->toISOString();
        $task['is_active'] = $task['is_active'] ?? 0;
        $task['parameters'] = $task['parameters'] ?? [];
        $task['frequency_parameters'] = $task['frequency_parameters'] ?? [];
        $task['constraints'] = $task['constraints'] ?? [];

        $tasks[] = $task;

        File::put($this->storageFile, json_encode($tasks, JSON_PRETTY_PRINT));

        return $task['id'];
    }

    public function update(int $id, array $data): bool
    {
        $tasks = json_decode(File::get($this->storageFile), true) ?? [];
        $updated = false;

        foreach ($tasks as $key => $task) {
            if ($task['id'] === $id && empty($task['deleted_at'])) {
                $tasks[$key] = array_merge($task, $data, ['updated_at' => now()->toISOString()]);
                $updated = true;
                break;
            }
        }

        if ($updated) {
            File::put($this->storageFile, json_encode($tasks, JSON_PRETTY_PRINT));
        }

        return $updated;
    }

    public function delete(int $id): bool
    {
        $tasks = json_decode(File::get($this->storageFile), true) ?? [];
        $found = false;

        // Remove the task completely instead of soft delete
        $tasks = array_filter($tasks, function($task) use ($id, &$found) {
            if ($task['id'] === $id) {
                $found = true;
                return false; // Remove this task
            }
            return true; // Keep other tasks
        });

        if ($found) {
            File::put($this->storageFile, json_encode(array_values($tasks), JSON_PRETTY_PRINT));
        }

        return $found;
    }

    private function getNextId(array $tasks): int
    {
        if (empty($tasks)) {
            return 1;
        }

        return max(array_column($tasks, 'id')) + 1;
    }
}