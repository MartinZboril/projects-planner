<?php

namespace App\Services\Data;

use App\Enums\TicketStatusEnum;
use App\Enums\Routes\{ProjectRouteEnum, TicketRouteEnum};
use App\Models\Ticket;
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
     * Save data for ticket.
     */
    public function save(Ticket $ticket, ValidatedInput $inputs): Ticket
    {
        $ticket = Ticket::updateOrCreate(
            ['id' => $ticket->id],
            [
                'status' => $ticket->status ?? TicketStatusEnum::open,
                'reporter_id' => $ticket->reporter_id ?? Auth::id(),
                'project_id' => $inputs->project_id,
                'assignee_id' => $inputs->assignee_id ?? null,
                'subject' => $inputs->subject,
                'type' => $inputs->type,
                'priority' => $inputs->priority,
                'due_date' => $inputs->due_date,
                'message' => $inputs->message,
            ]
        );

        $this->projectUserService->storeUser($ticket->project, $ticket->reporter);
        
        if($ticket->assignee_id) {
            $this->projectUserService->storeUser($ticket->project, $ticket->assignee);
        }

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
     * Save that ticket was converted to task.
     */
    public function convert(Ticket $ticket): void
    {
        $ticket->update(['status' => TicketStatusEnum::archive, 'is_convert' => true]);
    }

    /**
     * Mark selected ticket.
     */
    public function mark(Ticket $ticket): Ticket
    {
        $ticket->is_marked = !$ticket->is_marked;
        $ticket->save();
        return $ticket;
    }

    /**
     * Set up redirect for the action.
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