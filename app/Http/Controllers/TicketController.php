<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Project;
use App\Models\User;
use App\Services\TicketService;
use App\Services\TaskService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject' => ['required', 'string', 'max:255'],
            'project_id' => ['required', 'integer', 'exists:projects,id'],
            'assignee_id' => ['sometimes', 'required', 'integer', 'exists:users,id'],
            'type' => ['required', 'integer', 'in:1,2,3,4'],
            'priority' => ['required', 'integer', 'in:1,2,3,4'],
            'due_date' => ['required', 'date'],
            'message' => ['required', 'max:65553'],
            'redirect' => ['in:tickets,projects'],
        ]);

        if ($validator->fails()) {
            return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
        }

        $ticket = $this->ticketService->store($request);
        $this->ticketService->flash('create');

        $redirectAction = (($request->redirect == 'projects') ? 'project_' : '') . (($request->create_and_close) ? 'tickets' : 'ticket');
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
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ticket $ticket)
    {
        $validator = Validator::make($request->all(), [
            'subject' => ['required', 'string', 'max:255'],
            'project_id' => ['required', 'integer', 'exists:projects,id'],
            'assignee_id' => ['required', 'integer', 'exists:users,id'],
            'type' => ['required', 'integer', 'in:1,2,3,4'],
            'priority' => ['required', 'integer', 'in:1,2,3,4'],
            'status' => ['required', 'integer', 'in:1,2,3'],
            'due_date' => ['required', 'date'],
            'message' => ['required', 'max:65553'],
            'redirect' => ['in:tickets,projects'],
        ]);

        if ($validator->fails()) {
            return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
        }

        $ticket = $this->ticketService->update($ticket, $request);
        $this->ticketService->flash('update');

        $redirectAction = (($request->redirect == 'projects') ? 'project_' : '') . (($request->save_and_close) ? 'tickets' : 'ticket');
        return $this->ticketService->redirect($redirectAction, $ticket);
    }

    /**
     * Change status of the ticket.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function change(Request $request, Ticket $ticket)
    {
        $validator = Validator::make($request->all(), [
            'status' => ['required', 'integer', 'in:1,2,3'],
            'redirect' => ['in:tickets,projects'],
        ]);

        if ($validator->fails()) {
            return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
        }

        $ticket = $this->ticketService->change($ticket, $request);
        $flashAction = match ($request->status) {
            1 => 'open',
            2 => 'close',
            3 => 'archive',
            default => ''
        };
        $this->ticketService->flash($flashAction);

        $redirectAction = (($request->redirect == 'projects') ? 'project_' : '') . 'ticket';
        return $this->ticketService->redirect($redirectAction, $ticket);
    }

    /**
     * Convert the ticket.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function convert(Request $request, Ticket $ticket)
    {
        $validator = Validator::make($request->all(), [
            'redirect' => ['in:tickets,projects'],
        ]);
        
        if ($validator->fails()) {
            return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
        }

        $task = $this->ticketService->convert($ticket);      
        $taskService = new TaskService;
        $taskService->flash('create');

        $redirectAction =  (($request->redirect == 'projects') ? 'project_' : '') . 'task';
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
