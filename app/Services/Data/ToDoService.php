<?php

namespace App\Services\Data;

use App\Models\{Task, ToDo};

class ToDoService
{
    /**
     * Save data for todo.
     */
    public function handleSave(ToDo $todo, array $inputs, Task $task): void
    {
        // Prepare fields
        $inputs['task_id'] = $task->id;
        // Save timer
        $todo->fill($inputs)->save();
    }

    /**
     * Un/check the todo.
     */
    public function handleCheck(ToDo $todo): ToDo
    {
        $todo->update(['is_marked' => !$todo->is_finished]);
        return $todo->fresh();
    }
}