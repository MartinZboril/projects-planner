<?php

namespace App\View\Components\Task;

use Illuminate\View\Component;

class Table extends Component
{
    public $tasks;
    public $tableId;

    public function __construct($tasks, $tableId)
    {
        $this->tasks = $tasks;
        $this->tableId = $tableId;
    }

    public function render()
    {
        return view('components.task.table');
    }
}
