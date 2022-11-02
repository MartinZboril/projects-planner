<?php

namespace App\Services\Data;

use App\Models\ToDo;
use Illuminate\Http\RedirectResponse;

class ToDoService
{
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
    
    public function check(ToDo $todo, array $fields): ToDo
    {
        ToDo::where('id', $todo->id)
                ->update([
                    'is_finished' => $fields['action'],
                ]);

        return $todo;
    }

    public function redirect(string $action, ToDo $todo): RedirectResponse 
    {   
        switch ($action) {
            case 'task':
                return redirect()->route('tasks.detail', ['task' => $todo->task]);
                break;
            case 'project_task':
                return redirect()->route('projects.task.detail', ['project' => $todo->task->project, 'task' => $todo->task]);
                break;
            default:
                return redirect()->back();
        }
    }
}