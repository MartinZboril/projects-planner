<?php

namespace App\View\Components\Ticket;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;
use App\Models\Ticket;

class Table extends Component
{
    public function __construct(public Collection $tickets, public string $tableId, public string $type="tickets")
    {
        $this->tickets->each(function (Ticket $ticket) use($type) {
            $ticket->edit_route = $this->getEditRoute($ticket, $type);
            $ticket->show_route = $this->getShowRoute($ticket, $type);
            $ticket->project_show_route = route('projects.show', $ticket->project);
        });
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
