<?php

namespace App\View\Components\Task;

use Illuminate\View\Component;

class Table extends Component
{
    public $tasks;
    public $tableId;
    public $type;

    public function __construct($tasks, $tableId, $type)
    {
        $this->tasks = $tasks;
        $this->tableId = $tableId;
        $this->type = $type;
    }

    public function render()
    {
        return view('components.task.table');
    }
}
