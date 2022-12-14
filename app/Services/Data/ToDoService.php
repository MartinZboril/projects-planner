<?php

namespace App\Services\Data;

use App\Enums\Routes\{ProjectRouteEnum, TaskRouteEnum};
use App\Models\ToDo;
use App\Services\RouteService;
use App\Services\Dashboard\TaskDashboard;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\ValidatedInput;

class ToDoService
{
    /**
     * Store new todo.
     */
    public function store(ValidatedInput $inputs): ToDo
    {
        $todo = new ToDo;
        $todo->task_id = $inputs->task_id;

        return $this->save($todo, $inputs);
    }

    /**
     * Update todo.
     */
    public function update(ToDo $todo, ValidatedInput $inputs): ToDo
    {
        return $this->save($todo, $inputs);
    }
    
    /**
     * Save data for todo.
     */
    protected function save(ToDo $todo, ValidatedInput $inputs)
    {
        $todo->name = $inputs->name;
        $todo->deadline = $inputs->deadline;
        $todo->is_finished = $inputs->has('is_finished');
        $todo->description = $inputs->description;
        $todo->save();

        return $todo;
    }

    /**
     * Un/check the todo.
     */
    public function check(ToDo $todo, bool $action): ToDo
    {
        $todo->is_finished = $action;
        $todo->save();

        return $todo;
    }

    /**
     * Set up redirect for the action
     */
    public function setUpRedirect(string $parent, string $type, ToDo $todo): RedirectResponse
    {
        switch ($parent) {
            case 'projects':
                $redirectAction = $type ? ProjectRouteEnum::Tasks : ProjectRouteEnum::TasksDetail;
                $redirectVars = $type ? ['project' => $todo->task->project] : ['project' => $todo->task->project, 'task' => $todo->task];
                break;                

            default:
                $redirectAction = $type ? TaskRouteEnum::Index : TaskRouteEnum::Detail;
                $redirectVars = $type ? [] : ['task' => $todo->task];
                break;
        }
        
        return (new RouteService)->redirect($redirectAction->value, $redirectVars);
    }
}