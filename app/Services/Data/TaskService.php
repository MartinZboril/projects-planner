<?php

namespace App\Services\Data;

use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

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
    public function store(array $fields): Task
    {
        $task = new Task;
        $task->project_id = $fields['project_id'];
        $task->milestone_id = isset($fields['milestone_id']) ? $fields['milestone_id'] : null;
        $task->status = 1;
        $task->author_id = Auth::id();
        $task->user_id = $fields['user_id'];
        $task->name = $fields['name'];
        $task->start_date = $fields['start_date'];
        $task->due_date = $fields['due_date'];
        $task->description = $fields['description'];
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
    public function update(Task $task, array $fields): Task
    {
        Task::where('id', $task->id)
                    ->update([
                        'project_id' => $fields['project_id'],
                        'milestone_id' => isset($fields['milestone_id']) ? $fields['milestone_id'] : null,
                        'user_id' => $fields['user_id'],
                        'name' => $fields['name'],
                        'start_date' => $fields['start_date'],
                        'due_date' => $fields['due_date'],
                        'description' => $fields['description'],
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
    public function change(Task $task, array $fields): Task
    {
        Task::where('id', $task->id)
                    ->update([
                        'status' => $fields['status'],
                        'is_returned' => false,
                    ]);

        return $task;
    }
        
    /**
     * Pause work on the task
     */
    public function pause(Task $task, array $fields): Task
    {
        Task::where('id', $task->id)
                ->update([
                    'is_stopped' => $fields['action'],
                ]);

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