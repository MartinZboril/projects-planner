<?php

namespace App\View\Components\Task;

use App\Models\Milestone;
use App\Models\Project;
use App\Models\Task;
use Illuminate\View\Component;

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
        $milestones = $this->project ?? false
                            ? Milestone::where('project_id', $this->project->id)->get()
                            : Milestone::where('project_id', $this->task->project_id)->get();

        $users = $this->project ?? false
                            ? $this->project->team
                            : ($this->task->project ?? false ? $this->task->project->team : []);

        return view('components.task.fields', ['projects' => Project::all(), 'milestones' => $milestones, 'users' => $users]);
    }
}
