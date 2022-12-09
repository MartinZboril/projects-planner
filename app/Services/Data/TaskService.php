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
        $task->status = TaskStatusEnum::new;
        $task->author_id = Auth::id();

        $task = $this->save($task, $inputs);

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
        $task = $this->save($task, $inputs);

        if(!$this->projectUserService->workingOnProject($task->project_id, $task->author_id)) {
            $this->projectUserService->store($task->project_id, $task->author_id);
        }

        if(!$this->projectUserService->workingOnProject($task->project_id, $task->user_id)) {
            $this->projectUserService->store($task->project_id, $task->user_id);
        }

        return $task;
    }

    /**
     * Save data for task.
     */
    protected function save(Task $task, ValidatedInput $inputs)
    {
        $task->project_id = $inputs->project_id;
        $task->milestone_id = $inputs->has('milestone_id') ? $inputs->milestone_id : null;
        $task->user_id = $inputs->user_id;
        $task->name = $inputs->name;
        $task->start_date = $inputs->start_date;
        $task->due_date = $inputs->due_date;
        $task->description = $inputs->description;
        $task->save();

        return $task;
    }
    
    /**
     * Change working status of the task
     */
    public function change(Task $task, int $status): Task
    {
        $task->status = $status;
        $task->is_returned = ($task->status == TaskStatusEnum::complete && $status == TaskStatusEnum::new->value) ? true : false;
        $task->save();

        return $task;
    }
        
    /**
     * Pause work on the task.
     */
    public function pause(Task $task, bool $pause): Task
    {
        $task->is_stopped = $pause;
        $task->save();

        return $task;
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