<?php

namespace App\View\Components\Project;

use Illuminate\View\Component;

class Table extends Component
{
    public $projects;
    public $tableId;

    public function __construct($projects, $tableId)
    {
        $this->projects = $projects;
        $this->tableId = $tableId;
    }

    public function render()
    {
        return view('components.project.table');
    }
}
