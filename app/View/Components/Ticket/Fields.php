<?php

namespace App\View\Components\Ticket;

use Illuminate\View\Component;
use App\Models\{Project, User};

class Fields extends Component
{
    public $ticket;
    public $type;

    public function __construct($ticket, $type)
    {
        $this->ticket = $ticket;
        $this->type = $type;
    }

    public function render()
    {
        return view('components.ticket.fields', ['projects' => Project::all(), 'users' => User::all()]);
    }
}
