<?php

namespace App\Services;

use App\Models\Ticket;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class TicketService
{
    protected $projectUserService;

    public function __construct(ProjectUserService $projectUserService)
    {
        $this->projectUserService = $projectUserService;
    }

    public function store(Request $request): Ticket
    {
        $ticket = new Ticket;
        $ticket->subject = $request->subject;
        $ticket->project_id = $request->project_id;
        $ticket->reporter_id = Auth::id();
        $ticket->assignee_id = $request->assignee_id;
        $ticket->type = $request->type;
        $ticket->priority = $request->priority;
        $ticket->due_date = $request->due_date;
        $ticket->message = $request->message;
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

    public function update(Ticket $ticket, Request $request): Ticket
    {
        Ticket::where('id', $ticket->id)
                    ->update([
                        'subject' => $request->subject,
                        'project_id' => $request->project_id,
                        'assignee_id' => $request->assignee_id,
                        'status' => $request->status,
                        'type' => $request->type,
                        'priority' => $request->priority,
                        'due_date' => $request->due_date,
                        'message' => $request->message,
                    ]);

        if(!$request->assignee_id) {
            return $ticket;
        }

        if(!$this->projectUserService->workingOnProject($ticket->project_id, $request->assignee_id)) {
            $this->projectUserService->store($ticket->project_id, $request->assignee_id);
        }

        return $ticket;
    }

    public function change(Ticket $ticket, Request $request): Ticket
    {
        Ticket::where('id', $ticket->id)
                    ->update([
                        'status' => $request->status,
                    ]);

        return $ticket;
    }

    public function convert(Ticket $ticket): Task
    {
        $task = new Task();
        $task->name = $ticket->subject;
        $task->project_id = $ticket->project_id;
        $task->status_id = 1;
        $task->author_id = $ticket->reporter_id;
        $task->user_id = $ticket->assignee_id;
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

    public function flash(string $action): void
    {
        switch ($action) {
            case 'create':
                Session::flash('message', __('messages.ticket.create'));
                Session::flash('type', 'info');
                break;
            case 'update':
                Session::flash('message', __('messages.ticket.update'));
                Session::flash('type', 'info');
                break;
            case 'finish':
                Session::flash('message', __('messages.ticket.open'));
                Session::flash('type', 'info');
                break;
            case 'return':
                Session::flash('message', __('messages.ticket.close'));
                Session::flash('type', 'info');
                break;
            case 'archive':
                Session::flash('message', __('messages.ticket.archive'));
                Session::flash('type', 'info');
                break;
            default:
                Session::flash('message', __('messages.complete'));
                Session::flash('type', 'info');
        }
    }

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