<?php

namespace App\Services\Data;

use App\Enums\Routes\{ProjectRouteEnum, TaskRouteEnum};
use App\Models\ToDo;
use App\Services\RouteService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\ValidatedInput;

class ToDoService
{
    /**
     * Save data for todo.
     */
    public function save(ToDo $todo, ValidatedInput $inputs)
    {
        return ToDo::updateOrCreate(
            ['id' => $todo->id],
            [
                'task_id' => $todo->task_id ?? $inputs->task_id,
                'name' => $inputs->name,
                'due_date' => $inputs->due_date,
                'is_finished' => $inputs->has('is_finished'),
                'description' => $inputs->description,
            ]
        );
    }

    /**
     * Un/check the todo.
     */
    public function check(ToDo $todo): ToDo
    {
        $todo->is_finished = !$todo->is_finished;
        $todo->save();
        return $todo;
    }

    /**
     * Set up redirect for the action.
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