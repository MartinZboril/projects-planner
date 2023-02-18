<?php

namespace App\View\Components\Task;

use Illuminate\View\Component;
use App\Models\{Project, User};

class Fields extends Component
{
    public $task;
    public $type;
    public $project;
    public $closeRoute;

    public function __construct($task, $type, $project)
    {
        $this->task = $task;
        $this->type = $type;
        $this->project = $project;
        $this->closeRoute = $this->getCloseRoute($task, $project, $type);
    }

    public function render()
    {
        return view('components.task.fields', ['projects' => Project::all(), 'users' => User::all()]);
    }

    private function getCloseRoute($task, $project, $type)
    {
        if ($type === 'edit') {
            return $project
                        ? route('projects.tasks.show', ['project' => $task->project, 'task' => $task])
                        : route('tasks.show', $task);
        } else {
            return $project
                        ? route('projects.tasks.index', $project)
                        : route('tasks.index');
        }
    }
}
