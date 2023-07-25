<?php

namespace App\Services\Data;

use App\Models\Task;
use App\Models\Todo;

class TodoService
{
    /**
     * Save data for todo.
     */
    public function handleSave(Todo $todo, array $inputs, Task $task): void
    {
        // Prepare fields
        $inputs['task_id'] = $task->id;
        // Save timer
        $todo->fill($inputs)->save();
    }

    /**
     * Un/check the todo.
     */
    public function handleCheck(Todo $todo): Todo
    {
        $todo->update(['is_finished' => ! $todo->is_finished]);

        return $todo->fresh();
    }
}
