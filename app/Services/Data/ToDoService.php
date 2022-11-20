<?php

namespace App\Services\Data;

use App\Models\ToDo;
use App\Services\Dashboard\TaskDashboard;
use Illuminate\Http\RedirectResponse;

class ToDoService
{
    /**
     * Store new todo.
     */
    public function store(array $fields): ToDo
    {
        $todo = new ToDo;
        $todo->task_id = $fields['task_id'];
        $todo->name = $fields['name'];
        $todo->deadline = $fields['deadline'];
        $todo->description = $fields['description'];
        $todo->save();

        return $todo;
    }

    /**
     * Update todo.
     */
    public function update(ToDo $todo, array $fields): ToDo
    {
        ToDo::where('id', $todo->id)
                    ->update([
                        'name' => $fields['name'],
                        'deadline' => $fields['deadline'],
                        'is_finished' => isset($fields['is_finished']) ? 1 : 0,
                        'description' => $fields['description'],
                    ]);

        return $todo;
    }
    
    /**
     * Un/check the todo
     */
    public function check(ToDo $todo, array $fields): ToDo
    {
        ToDo::where('id', $todo->id)
                ->update([
                    'is_finished' => $fields['action'],
                ]);

        return $todo;
    }

    /**
     * Get route for the action
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