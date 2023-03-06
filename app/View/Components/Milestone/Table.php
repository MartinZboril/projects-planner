<?php

namespace App\View\Components\Milestone;

use Illuminate\View\Component;

class Table extends Component
{
    public function __construct(public string $tableId, public ?string $type='milestones')
    {
    }

    public function render()
    {
        return view('components.milestone.table');
    }
}
