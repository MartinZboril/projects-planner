<?php

namespace App\Services\Data;

use Illuminate\Support\ValidatedInput;
use App\Models\{Task, ToDo};

class ToDoService
{
    /**
     * Save data for todo.
     */
    public function handleSave(ToDo $todo, ValidatedInput $inputs, Task $task): void
    {
        ToDo::updateOrCreate(
            ['id' => $todo->id],
            [
                'task_id' => $task->id,
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
    public function handleCheck(ToDo $todo): ToDo
    {
        $todo->is_finished = !$todo->is_finished;
        $todo->save();
        return $todo;
    }
}