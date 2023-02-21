<?php

namespace App\View\Components\ToDo;

use Illuminate\View\Component;
use App\Models\{Task, ToDo};

class Fields extends Component
{
    public $todo;
    public $type;
    public $closeRoute;

    public function __construct(?ToDo $todo, Task $task, string $type, ?bool $isProject=false)
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
