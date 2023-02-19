<?php

namespace App\View\Components\Ticket;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;
use App\Models\Ticket;

class Table extends Component
{
    public $tickets;
    public $tableId;
    public $type;

    public function __construct(Collection $tickets, string $tableId, string $type="tickets")
    {
        $this->tickets = $tickets->each(function (Ticket $ticket) use($type) {
            $ticket->edit_route = $this->getEditRoute($ticket, $type);
            $ticket->show_route = $this->getShowRoute($ticket, $type);
        });
        $this->tableId = $tableId;
        $this->type = $type;
    }

    private function getEditRoute(?Ticket $ticket, string $type): string
    {
        return $type === 'projects'
                ? route('projects.tickets.edit', ['project' => $ticket->project, 'ticket' => $ticket])
                : route('tickets.edit', $ticket);
    }

    private function getShowRoute(?Ticket $ticket, string $type): string
    {
        return $type === 'projects'
                ? route('projects.tickets.show', ['project' => $ticket->project, 'ticket' => $ticket])
                : route('tickets.show', $ticket);
    }

    public function render()
    {
        return view('components.ticket.table');
    }
}
