<?php

namespace App\View\Components\Task;

use Illuminate\View\Component;

class Table extends Component
{
    public function __construct(public string $tableId, public ?string $type='tasks', public ?int $projectId=0, public ?int $milestoneId=0, public ?bool $overdue=false, public ?string $status='')
    {
    }

    public function render()
    {
        return view('components.task.table');
    }
}
