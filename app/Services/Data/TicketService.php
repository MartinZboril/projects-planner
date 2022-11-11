<?php

namespace App\Services\Data;

use App\Models\{Task, Ticket};
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

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
    public function store(array $fields): Ticket
    {
        $ticket = new Ticket;
        $ticket->project_id = $fields['project_id'];
        $ticket->reporter_id = Auth::id();
        $ticket->assignee_id = isset($fields['assignee_id']) ? $fields['assignee_id'] : null;
        $ticket->subject = $fields['subject'];
        $ticket->type = $fields['type'];
        $ticket->priority = $fields['priority'];
        $ticket->due_date = $fields['due_date'];
        $ticket->message = $fields['message'];
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
    public function update(Ticket $ticket, array $fields): Ticket
    {
        Ticket::where('id', $ticket->id)
                    ->update([
                        'project_id' => $fields['project_id'],
                        'assignee_id' => $fields['assignee_id'],
                        'subject' => $fields['subject'],
                        'type' => $fields['type'],
                        'priority' => $fields['priority'],
                        'due_date' => $fields['due_date'],
                        'message' => $fields['message'],
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
    public function change(Ticket $ticket, array $fields): Ticket
    {
        Ticket::where('id', $ticket->id)
                    ->update([
                        'status' => $fields['status'],
                    ]);

        return $ticket;
    }

    /**
     * Convert ticket to new task
     */
    public function convert(Ticket $ticket): Task
    {
        $task = new Task();
        $task->project_id = $ticket->project_id;
        $task->status = 1;
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