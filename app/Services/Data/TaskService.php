<?php

namespace App\Services\Data;

use App\Enums\TaskStatusEnum;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ValidatedInput;

class TaskService
{
    protected $projectUserService;

    public function __construct(ProjectUserService $projectUserService)
    {
        $this->projectUserService = $projectUserService;
    }

    /**
     * Store new task.
     */
    public function store(ValidatedInput $inputs): Task
    {
        $task = new Task;
        $task->project_id = $inputs->project_id;
        $task->milestone_id = $inputs->has('milestone_id') ? $inputs->milestone_id : null;
        $task->status = TaskStatusEnum::new;
        $task->author_id = Auth::id();
        $task->user_id = $inputs->user_id;
        $task->name = $inputs->name;
        $task->start_date = $inputs->start_date;
        $task->due_date = $inputs->due_date;
        $task->description = $inputs->description;
        $task->save();

        if(!$this->projectUserService->workingOnProject($task->project_id, $task->author_id)) {
            $this->projectUserService->store($task->project_id, $task->author_id);
        }

        if(!$this->projectUserService->workingOnProject($task->project_id, $task->user_id)) {
            $this->projectUserService->store($task->project_id, $task->user_id);
        }

        return $task;
    }

    /**
     * Update task.
     */
    public function update(Task $task, ValidatedInput $inputs): Task
    {
        Task::where('id', $task->id)
                    ->update([
                        'project_id' => $inputs->project_id,
                        'milestone_id' => $inputs->has('milestone_id') ? $inputs->milestone_id : null,
                        'user_id' => $inputs->user_id,
                        'name' => $inputs->name,
                        'start_date' => $inputs->start_date,
                        'due_date' => $inputs->due_date,
                        'description' => $inputs->description,
                    ]);

        if(!$this->projectUserService->workingOnProject($task->project_id, $task->author_id)) {
            $this->projectUserService->store($task->project_id, $task->author_id);
        }

        if(!$this->projectUserService->workingOnProject($task->project_id, $task->user_id)) {
            $this->projectUserService->store($task->project_id, $task->user_id);
        }

        return $task;
    }
    
    /**
     * Change working status of the task
     */
    public function change(Task $task, int $status): Task
    {
        Task::where('id', $task->id)
                    ->update([
                        'status' => $status,
                        'is_returned' => ($task->status == TaskStatusEnum::complete && $status == TaskStatusEnum::new->value) ? true : false,
                    ]);

        return $task->refresh();
    }
        
    /**
     * Pause work on the task
     */
    public function pause(Task $task, bool $pause): Task
    {
        Task::where('id', $task->id)
                ->update([
                    'is_stopped' => $pause,
                ]);

        return $task->refresh();
    }

    /**
     * Get route for the action
     */
    public function redirect(string $action, Task $task): RedirectResponse 
    {   
        switch ($action) {
            case 'tasks':
                return redirect()->route('tasks.index');
                break;
            case 'task':
                return redirect()->route('tasks.detail', ['task' => $task->id]);
                break;
            case 'project_tasks':
                return redirect()->route('projects.tasks', ['project' => $task->project]);
                break;
            case 'project_task':
                return redirect()->route('projects.task.detail', ['project' => $task->project, 'task' => $task]);
                break;
            case 'kanban':
                return redirect()->route('projects.kanban', ['project' => $task->project]);
                break;
            default:
                return redirect()->back();
        }
    }
}