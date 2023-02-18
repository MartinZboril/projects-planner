<?php

namespace App\View\Components\Milestone;

use Illuminate\View\Component;

class Table extends Component
{
    public $milestones;
    public $tableId;

    public function __construct($milestones, $tableId)
    {
        $this->milestones = $milestones;
        $this->tableId = $tableId;
    }

    public function render()
    {
        return view('components.milestone.table');
    }
}
