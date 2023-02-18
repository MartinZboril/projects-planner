<?php

namespace App\View\Components\Ticket;

use Illuminate\View\Component;

class Table extends Component
{
    public $tickets;
    public $tableId;
    public $type;

    public function __construct($tickets, $tableId, $type)
    {
        $this->tickets = $tickets;
        $this->tableId = $tableId;
        $this->type = $type;
    }

    public function render()
    {
        return view('components.ticket.table');
    }
}
