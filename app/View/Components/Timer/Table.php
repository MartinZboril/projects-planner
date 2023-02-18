<?php

namespace App\View\Components\Timer;

use Illuminate\View\Component;

class Table extends Component
{
    public $timers;
    public $tableId;

    public function __construct($timers, $tableId)
    {
        $this->timers = $timers;
        $this->tableId = $tableId;
    }

    public function render()
    {
        return view('components.timer.table');
    }
}
