<?php

namespace App\View\Components\Project;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;
use App\Models\Project;

class Table extends Component
{
    public function __construct(public Collection $projects, public string $tableId)
    {
        $this->projects->each(function (Project $project) {
            $project->edit_route = route('projects.edit', $project);
            $project->show_route = route('projects.show', $project);
            $project->client_show_route = route('clients.show', $project->client);
        });
    }

    public function render()
    {
        return view('components.project.table');
    }
}
