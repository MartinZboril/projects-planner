<?php

namespace App\Services\Data;

use App\Enums\TaskStatusEnum;
use App\Models\Task;
use App\Notifications\Task\UserAssignedNotification;
use App\Services\FileService;
use Illuminate\Support\Facades\Auth;

class TaskService
{
    public function __construct(
        private FileService $fileService,
    ) {
    }

    /**
     * Save data for task.
     */
    public function handleSave(Task $task, array $inputs, ?array $uploadedFiles = []): Task
    {
        $oldUserId = $task->user_id;
        // Prepare fields
        $inputs['status'] = $task->status ?? TaskStatusEnum::new;
        $inputs['author_id'] = $inputs['author_id'] ?? ($task->author_id ?? Auth::id());
        $inputs['milestone_id'] = $inputs['milestone_id'] ?? null;
        // Save task
        $task->fill($inputs)->save();
        // Upload files
        if ($uploadedFiles) {
            $this->handleUploadFiles($task, $uploadedFiles);
        }

        if ((int) $oldUserId !== (int) $task->user_id) {
            $task->user->notify(new UserAssignedNotification($task));
        }

        return $task;
    }

    /**
     * Upload tasks files.
     */
    public function handleUploadFiles(Task $task, array $uploadedFiles): void
    {
        foreach ($uploadedFiles as $uploadedFile) {
            $this->fileService->handleUpload($uploadedFile, 'tasks/files', $task);
        }
    }

    /**
     * Change working status of the task.
     */
    public function handleChangeStatus(Task $task, int $status): Task
    {
        $task->update([
            'is_returned' => ($task->status === TaskStatusEnum::complete && $status === TaskStatusEnum::new->value) ? true : false,
            'status' => $status,
        ]);

        return $task->fresh();
    }

    /**
     * Pause work on the task.
     */
    public function handlePause(Task $task): Task
    {
        $task->update(['is_stopped' => ! $task->is_stopped]);

        return $task->fresh();
    }

    /**
     * Mark selected task.
     */
    public function handleMark(Task $task): Task
    {
        $task->update(['is_marked' => ! $task->is_marked]);

        return $task->fresh();
    }

    /**
     * Delete selected task.
     */
    public function handleDelete(Task $task): void
    {
        $task->delete();
    }
}
