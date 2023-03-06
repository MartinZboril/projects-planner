<?php

namespace App\View\Components\Project;

use Illuminate\View\Component;

class Table extends Component
{
    public function __construct(public string $tableId, public ?bool $overdue=false)
    {
    }

    public function render()
    {
        return view('components.project.table');
    }
}
