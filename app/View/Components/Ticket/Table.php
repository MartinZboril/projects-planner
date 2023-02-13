<?php

namespace App\View\Components\Ticket;

use Illuminate\View\Component;

class Table extends Component
{
    public $tickets;
    public $tableId;

    public function __construct($tickets, $tableId)
    {
        $this->tickets = $tickets;
        $this->tableId = $tableId;
    }

    public function render()
    {
        return view('components.ticket.table');
    }
}
