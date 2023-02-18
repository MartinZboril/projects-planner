<?php

namespace App\View\Components\ToDo;

use Illuminate\View\Component;

class Fields extends Component
{
    public $todo;
    public $task;
    public $type;
    public $isProject;
    public $closeRoute;

    public function __construct($todo, $task, $type, $isProject)
    {
        $this->todo = $todo;
        $this->task = $task;
        $this->type = $type;
        $this->isProject = $isProject;
        $this->closeRoute = $this->getCloseRoute($task, $isProject);
    }

    public function render()
    {
        return view('components.todo.fields');
    }

    private function getCloseRoute($task, $isProject)
    {
        return $isProject
                    ? route('projects.tasks.show', ['project' => $task->project, 'task' => $task])
                    : route('tasks.show', $task);
    }    
}
