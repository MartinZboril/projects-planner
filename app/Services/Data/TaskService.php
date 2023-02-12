<?php

namespace App\Services\Data;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ValidatedInput;
use App\Enums\TaskStatusEnum;
use App\Models\{Comment, Task};
use App\Services\FileService;

class TaskService
{
    public function __construct(private ProjectUserService $projectUserService)
    {
    }

    /**
     * Save data for task.
     */
    public function handleSave(Task $task, ValidatedInput $inputs): Task
    {
        $task = Task::updateOrCreate(
            ['id' => $task->id],
            [
                'status' => $task->status ?? TaskStatusEnum::new,
                'author_id' => $inputs->author_id ?? ($task->author_id ?? Auth::id()),
                'project_id' => $inputs->project_id,
                'milestone_id' => $inputs->milestone_id ?? null,
                'user_id' => $inputs->user_id,
                'name' => $inputs->name,
                'start_date' => $inputs->start_date,
                'due_date' => $inputs->due_date,
                'description' => $inputs->description,
            ]
        );

        $this->projectUserService->handleStoreUser($task->project, $task->author);
        $this->projectUserService->handleStoreUser($task->project, $task->user);

        return $task;
    }
    
    /**
     * Upload tasks files.
     */
    public function handleUploadFiles(Task $task, Array $uploadedFiles): void
    {
        foreach ($uploadedFiles as $uploadedFile) {
            $task->files()->save((new FileService)->handleUpload($uploadedFile, 'tasks/files'));
        }
    }

    /**
     * Save tasks comments.
     */
    public function handleSaveComment(Task $task, Comment $comment): void
    {
        $task->comments()->save($comment);
    }

    /**
     * Change working status of the task.
     */
    public function handleChangeStatus(Task $task, int $status): Task
    {
        $task->status = $status;
        $task->is_returned = $task->isReturned() ? true : false;
        $task->save();
        return $task;
    }
        
    /**
     * Pause work on the task.
     */
    public function handlePause(Task $task): Task
    {
        $task->is_stopped = !$task->is_stopped;
        $task->save();
        return $task;
    }

    /**
     * Mark selected task.
     */
    public function handleMark(Task $task): Task
    {
        $task->is_marked = !$task->is_marked;
        $task->save();
        return $task;
    }
}