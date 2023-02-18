<?php

namespace App\View\Components\Ticket;

use Illuminate\View\Component;
use App\Models\{Project, User};

class Fields extends Component
{
    public $ticket;
    public $type;
    public $project;
    public $closeRoute;

    public function __construct($ticket, $type, $project)
    {
        $this->ticket = $ticket;
        $this->type = $type;
        $this->project = $project;
        $this->closeRoute = $this->getCloseRoute($ticket, $project, $type);
    }

    public function render()
    {
        return view('components.ticket.fields', ['projects' => Project::all(), 'users' => User::all()]);
    }
    
    private function getCloseRoute($ticket, $project, $type)
    {
        if ($type === 'edit') {
            return $project
                        ? route('projects.tickets.show', ['project' => $ticket->project, 'ticket' => $ticket])
                        : route('tickets.show', $ticket);
        } else {
            return $project
                        ? route('projects.tickets.index', $project)
                        : route('tickets.index');
        }
    }
}
