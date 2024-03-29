<?php

namespace App\View\Components\Todo;

use App\Models\Todo;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class Card extends Component
{
    public $todos;

    public $createFormRoute;

    public function __construct(Collection $todos, string $createFormRoute, ?string $type = 'tasks')
    {
        $this->todos = $todos->each(function (Todo $todo) use ($type) {
            $todo->edit_route = ($type === 'projects')
                                    ? route('projects.tasks.todos.edit', ['project' => $todo->task->project, 'task' => $todo->task, 'todo' => $todo])
                                    : route('tasks.todos.edit', ['task' => $todo->task, 'todo' => $todo]);
            $todo->check_route = route('tasks.todos.check', ['task' => $todo->task, 'todo' => $todo]);
            $todo->destroy_route = ($type === 'projects')
                                    ? route('projects.tasks.todos.destroy', ['project' => $todo->task->project, 'task' => $todo->task, 'todo' => $todo])
                                    : route('tasks.todos.destroy', ['task' => $todo->task, 'todo' => $todo]);
        });
        $this->createFormRoute = $createFormRoute;
    }

    public function render()
    {
        return view('components.todo.card');
    }
}
