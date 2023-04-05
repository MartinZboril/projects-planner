<?php

namespace App\View\Components\Ticket;

use Illuminate\View\Component;
use App\Models\{Project, Ticket, User};

class Fields extends Component
{
    public $ticket;
    public $type;
    public $project;
    public $closeRoute;

    public function __construct(?Ticket $ticket, ?Project $project, string $type)
    {
        $this->ticket = $ticket;
        $this->type = $type;
        $this->project = $project->exists ? $project : null;
        $this->closeRoute = $this->getCloseRoute($ticket, $project, $type);
    }

    public function render()
    {
        $users = $this->project ?? false 
                    ? $this->project->team
                    : ($this->ticket->project ?? false ? $this->ticket->project->team : []);


        return view('components.ticket.fields', ['projects' => Project::all(), 'users' => $users]);
    }
    
    private function getCloseRoute(?Ticket $ticket, ?Project $project, string $type): string
    {
        if ($type === 'edit') {
            return $project->exists
                        ? route('projects.tickets.show', ['project' => $ticket->project, 'ticket' => $ticket])
                        : route('tickets.show', $ticket);
        } else {
            return $project->exists
                        ? route('projects.tickets.index', $project)
                        : route('tickets.index');
        }
    }
}
