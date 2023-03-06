<?php

namespace App\View\Components\Ticket;

use Illuminate\View\Component;

class Table extends Component
{
    public function __construct(public string $tableId, public ?string $type="tickets", public ?int $projectId=0, public ?bool $overdue=false)
    {
    }

    public function render()
    {
        return view('components.ticket.table');
    }
}
