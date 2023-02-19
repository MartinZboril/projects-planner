<?php

namespace App\View\Components\Milestone;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;
use App\Models\Milestone;

class Table extends Component
{
    public function __construct(public Collection $milestones, public string $tableId, public ?string $type='milestones')
    {
        $this->milestones->each(function (Milestone $milestone) {
            $milestone->edit_route = route('projects.milestones.edit', ['project' => $milestone->project, 'milestone' => $milestone]);
            $milestone->show_route = route('projects.milestones.show', ['project' => $milestone->project, 'milestone' => $milestone]);
        });
    }

    public function render()
    {
        return view('components.milestone.table');
    }
}
