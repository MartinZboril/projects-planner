<?php

namespace App\Services\Data;

use App\Enums\TaskStatusEnum;
use App\Models\Task;
use App\Models\User;
use App\Notifications\Task\Status\CompletedTaskNotification;
use App\Notifications\Task\Status\InProgressedTaskNotification;
use App\Notifications\Task\Status\PausedTaskNotification;
use App\Notifications\Task\Status\ResumedTaskNotification;
use App\Notifications\Task\Status\ReturnedTaskNotification;
use App\Notifications\Task\UserAssignedNotification;
use App\Notifications\Task\UserUnassignedNotification;
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
        // Notify users about assignment to the task
        if ((int) $oldUserId !== (int) $task->user_id) {
            $task->user->notify(new UserAssignedNotification($task));

            if ($oldUserId) {
                User::find($oldUserId)->notify(new UserUnassignedNotification($task));
            }
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
        $isReturned = ($task->status === TaskStatusEnum::complete && $status === TaskStatusEnum::new->value) ? true : false;

        $task->update([
            'is_returned' => $isReturned,
            'status' => $status,
        ]);

        switch ($task->status) {
            case TaskStatusEnum::new:
                $isReturned ? $task->user->notify(new ReturnedTaskNotification($task)) : null;
                break;

            case TaskStatusEnum::in_progress:
                $task->author->notify(new InProgressedTaskNotification($task));
                break;

            case TaskStatusEnum::complete:
                $task->author->notify(new CompletedTaskNotification($task));
                break;
        }

        return $task->fresh();
    }

    /**
     * Pause work on the task.
     */
    public function handlePause(Task $task): Task
    {
        $task->update(['is_stopped' => ! $task->is_stopped]);

        $task->author->notify($task->is_stopped ? new PausedTaskNotification($task) : new ResumedTaskNotification($task));

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
