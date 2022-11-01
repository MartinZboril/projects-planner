<?php

namespace App\Http\Controllers;

use App\Http\Requests\Ticket\{ConvertTicketRequest, ChangeTicketRequest, StoreTicketRequest, UpdateTicketRequest};
use App\Models\{Project, Ticket, User};
use App\Services\{TaskService, TicketService, ProjectUserService};

class TicketController extends Controller
{  
    protected $ticketService;

    public function __construct(TicketService $ticketService)
    {
        $this->middleware('auth');
        $this->ticketService = $ticketService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('tickets.index', ['tickets' => Ticket::all()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tickets.create', ['projects' => Project::all(), 'users' => User::all()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTicketRequest $request)
    {
        $fields = $request->validated();
        $ticket = $this->ticketService->store($fields);
        $this->ticketService->flash('create');

        $redirectAction = (($fields['redirect'] == 'projects') ? 'project_' : '') . (isset(($fields['create_and_close'])) ? 'tickets' : 'ticket');
        return $this->ticketService->redirect($redirectAction, $ticket);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function detail(Ticket $ticket)
    {
        return view('tickets.detail', ['ticket' => $ticket]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function edit(Ticket $ticket)
    {
        return view('tickets.edit', ['ticket' => $ticket, 'projects' => Project::all(), 'users' => User::all()]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        $fields = $request->validated();
        $ticket = $this->ticketService->update($ticket, $fields);
        $this->ticketService->flash('update');

        $redirectAction = (($fields['redirect'] == 'projects') ? 'project_' : '') . ((isset($fields['save_and_close'])) ? 'tickets' : 'ticket');
        return $this->ticketService->redirect($redirectAction, $ticket);
    }

    /**
     * Change status of the ticket.
     */
    public function change(ChangeTicketRequest $request, Ticket $ticket)
    {
        $fields = $request->validated();
        $ticket = $this->ticketService->change($ticket, $fields);
        $flashAction = match ($fields['status']) {
            '1' => 'open',
            '2' => 'close',
            '3' => 'archive',
            default => ''
        };
        $this->ticketService->flash($flashAction);

        $redirectAction = (($fields['redirect'] == 'projects') ? 'project_' : '') . 'ticket';
        return $this->ticketService->redirect($redirectAction, $ticket);
    }

    /**
     * Convert the ticket to task.
     */
    public function convert(ConvertTicketRequest $request, Ticket $ticket)
    {
        $fields = $request->validated();
        $task = $this->ticketService->convert($ticket);      
        $taskService = new TaskService(new ProjectUserService);
        $taskService->flash('create');

        $redirectAction = (($fields['redirect'] == 'projects') ? 'project_' : '') . 'task';
        return $taskService->redirect($redirectAction, $task);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ticket $ticket)
    {
        //
    }
}
