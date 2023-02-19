<?php

namespace App\View\Components\Milestone;

use Illuminate\View\Component;
use App\Models\{Milestone, Project};

class Fields extends Component
{
    public $milestone;
    public $project;
    public $type;
    public $closeRoute;

    public function __construct(?Milestone $milestone, Project $project, string $type)
    {
        $this->milestone = $milestone;
        $this->project = $project;
        $this->type = $type;
        $this->closeRoute = $this->getCloseRoute($milestone, $project, $type);
    }

    private function getCloseRoute(?Milestone $milestone, Project $project, string $type): string
    {
        return $type === 'edit'
                    ? route('projects.milestones.show', ['project' => $project, 'milestone' => $milestone])
                    : route('projects.milestones.index', ['project' => $project]);
    }

    public function render()
    {
        return view('components.milestone.fields');
    }
}
