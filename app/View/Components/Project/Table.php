<?php

namespace App\View\Components\Project;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;
use App\Models\Project;

class Table extends Component
{
    public $projects;
    public $tableId;

    public function __construct(Collection $projects, string $tableId)
    {
        $this->projects = $projects->each(function (Project $project) {
            $project->edit_route = route('projects.edit', $project);
            $project->show_route = route('projects.show', $project);
        });
        $this->tableId = $tableId;
    }

    public function render()
    {
        return view('components.project.table');
    }
}
