<?php

namespace App\Services\Data;

use App\Models\ToDo;
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
     * Get route for the action.
     */
    public function redirect(string $action, ToDo $todo): RedirectResponse 
    {   
        switch ($action) {
            case 'task':
                return redirect()->route('tasks.detail', ['task' => $todo->task]);
                break;
            case 'project_task':
                return redirect()->route('projects.task.detail', ['project' => $todo->task->project, 'task' => $todo->task]);
                break;
                case 'dashboard_task':
                    return redirect()->route('dashboard.tasks', ['data' => (new TaskDashboard)->getDashboard()]);
                    break;
            default:
                return redirect()->back();
        }
    }
}