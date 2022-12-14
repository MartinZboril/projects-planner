<?php

namespace App\Services\Data;

use App\Enums\{TicketStatusEnum, TaskStatusEnum};
use App\Enums\Routes\{ProjectRouteEnum, TicketRouteEnum};
use App\Models\{Task, Ticket};
use App\Services\RouteService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ValidatedInput;

class TicketService
{
    protected $projectUserService;

    public function __construct(ProjectUserService $projectUserService)
    {
        $this->projectUserService = $projectUserService;
    }

    /**
     * Store new ticket.
     */
    public function store(ValidatedInput $inputs): Ticket
    {
        $ticket = new Ticket;
        $ticket->reporter_id = Auth::id();
        $ticket->status = TicketStatusEnum::open;

        $ticket = $this->save($ticket, $inputs);
        
        if(!$this->projectUserService->workingOnProject($ticket->project_id, $ticket->reporter_id)) {
            $this->projectUserService->store($ticket->project_id, $ticket->reporter_id);
        }

        if(!$ticket->assignee_id) {
            return $ticket;
        }

        if(!$this->projectUserService->workingOnProject($ticket->project_id, $ticket->assignee_id)) {
            $this->projectUserService->store($ticket->project_id, $ticket->assignee_id);
        }

        return $ticket;
    }

    /**
     * Update ticket.
     */
    public function update(Ticket $ticket, ValidatedInput $inputs): Ticket
    {
        $ticket = $this->save($ticket, $inputs);
        
        if(!$ticket->assignee_id) {
            return $ticket;
        }

        if(!$this->projectUserService->workingOnProject($ticket->project_id, $ticket->assignee_id)) {
            $this->projectUserService->store($ticket->project_id, $ticket->assignee_id);
        }

        return $ticket;
    }

    /**
     * Save data for ticket.
     */
    protected function save(Ticket $ticket, ValidatedInput $inputs)
    {
        $ticket->project_id = $inputs->project_id;
        $ticket->assignee_id = $inputs->has('assignee_id') ? $inputs->assignee_id : null;
        $ticket->subject = $inputs->subject;
        $ticket->type = $inputs->type;
        $ticket->priority = $inputs->priority;
        $ticket->due_date = $inputs->due_date;
        $ticket->message = $inputs->message;
        $ticket->save();

        return $ticket;
    }

    /**
     * Change working status of the ticket.
     */
    public function change(Ticket $ticket, int $status): Ticket
    {
        $ticket->status = $status;
        $ticket->save();

        return $ticket;
    }

    /**
     * Convert ticket to new task.
     */
    public function convert(Ticket $ticket): Task
    {
        $task = new Task();
        $task->project_id = $ticket->project_id;
        $task->status = TaskStatusEnum::new;
        $task->author_id = $ticket->reporter_id;
        $task->user_id = $ticket->assignee_id;
        $task->name = $ticket->subject;
        $task->start_date = $ticket->created_at;
        $task->due_date = $ticket->due_date;
        $task->description = $ticket->message;
        $task->save();

        if(!$this->projectUserService->workingOnProject($ticket->project_id, $ticket->assignee_id)) {
            $this->projectUserService->store($ticket->project_id, $ticket->assignee_id);
        }

        $ticket->status = TicketStatusEnum::archive;
        $ticket->is_convert = true;
        $ticket->save();

        return $task;
    }

    /**
     * Set up redirect for the action
     */
    public function setUpRedirect(string $parent, string $type, Ticket $ticket): RedirectResponse
    {
        switch ($parent) {
            case 'projects':
                $redirectAction = $type ? ProjectRouteEnum::Tickets : ProjectRouteEnum::TicketsDetail;
                $redirectVars = $type ? ['project' => $ticket->project] : ['project' => $ticket->project, 'ticket' => $ticket];
                break;                

            default:
                $redirectAction = $type ? TicketRouteEnum::Index : TicketRouteEnum::Detail;
                $redirectVars = $type ? [] : ['ticket' => $ticket];
                break;
        }
        
        return (new RouteService)->redirect($redirectAction->value, $redirectVars);
    }
}