<?php

namespace App\View\Components\Todo;

use App\Models\Task;
use App\Models\Todo;
use Illuminate\View\Component;

class Fields extends Component
{
    public $todo;

    public $type;

    public $closeRoute;

    public function __construct(?Todo $todo, Task $task, string $type, ?bool $isProject = false)
    {
        $this->todo = $todo;
        $this->type = $type;
        $this->closeRoute = $this->getCloseRoute($task, $isProject);
    }

    public function render()
    {
        return view('components.todo.fields');
    }

    private function getCloseRoute(Task $task, bool $isProject)
    {
        return $isProject
                    ? route('projects.tasks.show', ['project' => $task->project, 'task' => $task])
                    : route('tasks.show', $task);
    }
}
