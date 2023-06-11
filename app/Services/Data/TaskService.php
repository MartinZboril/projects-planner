<?php

namespace App\Services\Data;

use Illuminate\Support\Facades\Auth;
use App\Enums\TaskStatusEnum;
use App\Models\{Comment, Task};
use App\Services\FileService;

class TaskService
{
    public function __construct(
        private FileService $fileService,
    ) {}

    /**
     * Save data for task.
     */
    public function handleSave(Task $task, array $inputs, ?Array $uploadedFiles=[]): Task
    {
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
        return $task;
    }
    
    /**
     * Upload tasks files.
     */
    public function handleUploadFiles(Task $task, Array $uploadedFiles): void
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
            'is_returned' => ($task->status === TaskStatusEnum::complete && $changedStatus === TaskStatusEnum::new->value) ? true : false,
            'status' => $status,
        ]);
        return $task->fresh();
    }
        
    /**
     * Pause work on the task.
     */
    public function handlePause(Task $task): Task
    {
        $task->update(['is_stopped' => !$task->is_stopped]);
        return $task->fresh();
    }

    /**
     * Mark selected task.
     */
    public function handleMark(Task $task): Task
    {
        $task->update(['is_marked' => !$task->is_marked]);
        return $task->fresh();
    }
}