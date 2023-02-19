<?php

namespace App\View\Components\Task;

use Illuminate\View\Component;
use App\Models\{Milestone, Project, Task, User};

class Fields extends Component
{
    public $task;
    public $type;
    public $project;
    public $closeRoute;

    public function __construct(?Task $task, string $type, ?Project $project)
    {
        $this->task = $task;
        $this->type = $type;
        $this->project = $project->exists ? $project : null;
        $this->closeRoute = $this->getCloseRoute($task, $project, $type);
    }

    private function getCloseRoute(?Task $task, ?Project $project, string $type)
    {
        if ($type === 'edit') {
            return $project->exists
                        ? route('projects.tasks.show', ['project' => $task->project, 'task' => $task])
                        : route('tasks.show', $task);
        } else {
            return $project->exists
                        ? route('projects.tasks.index', $project)
                        : route('tasks.index');
        }
    }

    public function render()
    {
        return view('components.task.fields', ['projects' => Project::all(), 'users' => User::all(), 'milestones' => $this->project ?? false ? Milestone::where('project_id', $this->project->id)->get() : null, 'users' => User::all()]);
    }
}
