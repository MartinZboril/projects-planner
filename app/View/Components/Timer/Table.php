<?php

namespace App\View\Components\Timer;

use Illuminate\View\Component;

class Table extends Component
{
    public function __construct(public string $tableId, public string $type='timers', public ?int $projectId=0)
    {
    }

    public function render()
    {
        return view('components.timer.table');
    }
}
