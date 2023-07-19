<?php

namespace App\Services\Data;

use App\Enums\TaskStatusEnum;
use App\Events\Task\Status\TaskCompleted;
use App\Events\Task\Status\TaskInProgressed;
use App\Events\Task\Status\TaskPaused;
use App\Events\Task\Status\TaskResumed;
use App\Events\Task\Status\TaskReturned;
use App\Events\Task\TaskMilestoneChanged;
use App\Events\Task\TaskUserChanged;
use App\Models\Milestone;
use App\Models\Task;
use App\Models\User;
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
        $oldMilestoneId = $task->milestone_id;
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
            TaskUserChanged::dispatch($task, $task->user, ($oldUserId) ? User::find($oldUserId) : null);
        }
        // Log activity about milestone assignment to the task
        if ((int) $oldMilestoneId !== (int) $task->milestone_id) {
            TaskMilestoneChanged::dispatch($task, $task->milestone, ($oldMilestoneId) ? Milestone::find($oldMilestoneId) : null);
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
                $isReturned ? TaskReturned::dispatch($task) : null;
                break;

            case TaskStatusEnum::in_progress:
                TaskInProgressed::dispatch($task);
                break;

            case TaskStatusEnum::complete:
                TaskCompleted::dispatch($task);
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
        $task->is_stopped ? TaskPaused::dispatch($task) : TaskResumed::dispatch($task);

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
