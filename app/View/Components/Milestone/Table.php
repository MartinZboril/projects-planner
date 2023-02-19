<?php

namespace App\View\Components\Milestone;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;
use App\Models\Milestone;

class Table extends Component
{
    public $milestones;
    public $tableId;
    public $type;

    public function __construct(Collection $milestones, string $tableId, ?string $type='milestones')
    {
        $this->milestones = $milestones->each(function (Milestone $milestone) {
            $milestone->edit_route = route('projects.milestones.edit', ['project' => $milestone->project, 'milestone' => $milestone]);
            $milestone->show_route = route('projects.milestones.show', ['project' => $milestone->project, 'milestone' => $milestone]);
        });
        $this->tableId = $tableId;
        $this->type = $type;
    }

    public function render()
    {
        return view('components.milestone.table');
    }
}
