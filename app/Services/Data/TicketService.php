<?php

namespace App\Services\Data;

use App\Enums\{TicketStatusEnum, TaskStatusEnum};
use App\Models\{Task, Ticket};
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
        $ticket->project_id = $inputs->project_id;
        $ticket->reporter_id = Auth::id();
        $ticket->assignee_id = $inputs->has('assignee_id') ? $inputs->assignee_id : null;
        $ticket->subject = $inputs->subject;
        $ticket->type = $inputs->type;
        $ticket->priority = $inputs->priority;
        $ticket->due_date = $inputs->due_date;
        $ticket->message = $inputs->message;
        $ticket->status = TicketStatusEnum::open;
        $ticket->save();
        
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
        Ticket::where('id', $ticket->id)
                    ->update([
                        'project_id' => $inputs->project_id,
                        'assignee_id' => $inputs->assignee_id,
                        'subject' => $inputs->subject,
                        'type' => $inputs->type,
                        'priority' => $inputs->priority,
                        'due_date' => $inputs->due_date,
                        'message' => $inputs->message,
                    ]);

        $ticket = Ticket::find($ticket->id);
        
        if(!$ticket->assignee_id) {
            return $ticket;
        }

        if(!$this->projectUserService->workingOnProject($ticket->project_id, $ticket->assignee_id)) {
            $this->projectUserService->store($ticket->project_id, $ticket->assignee_id);
        }

        return $ticket;
    }

    /**
     * Change working status of the ticket
     */
    public function change(Ticket $ticket, int $status): Ticket
    {
        Ticket::where('id', $ticket->id)
                    ->update([
                        'status' => $status,
                    ]);

        return $ticket->fresh();
    }

    /**
     * Convert ticket to new task
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

        Ticket::where('id', $ticket->id)
                    ->update([
                        'status' => 3,
                        'is_convert' => true
                    ]);  

        return $task;
    }

    /**
     * Get route for the action
     */
    public function redirect(string $action, Ticket $ticket): RedirectResponse 
    {   
        switch ($action) {
            case 'tickets':
                return redirect()->route('tickets.index');
                break;
            case 'ticket':
                return redirect()->route('tickets.detail', ['ticket' => $ticket]);
                break;
            case 'project_tickets':
                return redirect()->route('projects.tickets', ['project' => $ticket->project]);
                break;
            case 'project_ticket':
                return redirect()->route('projects.ticket.detail', ['project' => $ticket->project, 'ticket' => $ticket]);
                break;
            default:
                return redirect()->back();
        }
    }
}