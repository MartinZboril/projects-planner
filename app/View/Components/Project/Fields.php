<?php

namespace App\View\Components\Project;

use Illuminate\View\Component;
use App\Models\{Client, Project, User};

class Fields extends Component
{
    public $project;
    public $type;
    public $closeRoute;

    public function __construct(?Project $project, string $type)
    {
        $this->project = $project;
        $this->type = $type;
        $this->closeRoute = $this->getCloseRoute($project, $type);
    }

    private function getCloseRoute(?Project $project, string $type): string
    {
        return $type === 'edit'
                    ? route('projects.show', $project)
                    : route('projects.index');
    }

    public function render()
    {
        return view('components.project.fields', ['clients' => Client::all(), 'users' => User::all()]);
    }
}
