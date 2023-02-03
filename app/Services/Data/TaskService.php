<?php

namespace App\Services\Data;

use App\Enums\TaskStatusEnum;
use App\Enums\Routes\{ProjectRouteEnum, TaskRouteEnum};
use App\Models\Task;
use App\Services\RouteService;
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
     * Save data for task.
     */
    public function save(Task $task, ValidatedInput $inputs): Task
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

        $this->projectUserService->storeUser($task->project, $task->author);
        $this->projectUserService->storeUser($task->project, $task->user);

        return $task;
    }
    
    /**
     * Change working status of the task.
     */
    public function change(Task $task, int $status): Task
    {
        $task->status = $status;
        $task->is_returned = $task->isReturned() ? true : false;
        $task->save();
        return $task;
    }
        
    /**
     * Pause work on the task.
     */
    public function pause(Task $task): Task
    {
        $task->is_stopped = !$task->is_stopped;
        $task->save();
        return $task;
    }

    /**
     * Mark selected task.
     */
    public function mark(Task $task): Task
    {
        $task->is_marked = !$task->is_marked;
        $task->save();
        return $task;
    }

    /**
     * Set up redirect for the action.
     */
    public function setUpRedirect(string $parent, string $type, Task $task): RedirectResponse
    {
        switch ($parent) {
            case 'projects':
                $redirectAction = $type ? ProjectRouteEnum::Tasks : ProjectRouteEnum::TasksDetail;
                $redirectVars = $type ? ['project' => $task->project] : ['project' => $task->project, 'task' => $task];
                break;                

            default:
                $redirectAction = $type ? TaskRouteEnum::Index : TaskRouteEnum::Detail;
                $redirectVars = $type ? [] : ['task' => $task];
                break;
        }
        
        return (new RouteService)->redirect($redirectAction->value, $redirectVars);
    }
}