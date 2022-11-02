<?php

namespace App\Http\Controllers;

use App\Http\Requests\Ticket\{ConvertTicketRequest, ChangeTicketRequest, StoreTicketRequest, UpdateTicketRequest};
use App\Models\{Project, Ticket, User};
use App\Services\FlashService;
use App\Services\Data\{TaskService, TicketService, ProjectUserService};
use Exception;
use Illuminate\Support\Facades\Log;

class TicketController extends Controller
{  
    protected $ticketService;
    protected $flashService;

    public function __construct(TicketService $ticketService, FlashService $flashService)
    {
        $this->middleware('auth');
        $this->ticketService = $ticketService;
        $this->flashService = $flashService;
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
        try {
            $fields = $request->validated();
            $ticket = $this->ticketService->store($fields);
            $this->flashService->flash(__('messages.ticket.create'), 'info');

            $redirectAction = (($fields['redirect'] == 'projects') ? 'project_' : '') . (isset(($fields['create_and_close'])) ? 'tickets' : 'ticket');
            return $this->ticketService->redirect($redirectAction, $ticket);
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
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
        try {
            $fields = $request->validated();
            $ticket = $this->ticketService->update($ticket, $fields);
            $this->flashService->flash(__('messages.ticket.update'), 'info');

            $redirectAction = (($fields['redirect'] == 'projects') ? 'project_' : '') . ((isset($fields['save_and_close'])) ? 'tickets' : 'ticket');
            return $this->ticketService->redirect($redirectAction, $ticket);
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
    }

    /**
     * Change status of the ticket.
     */
    public function change(ChangeTicketRequest $request, Ticket $ticket)
    {
        try {
            $fields = $request->validated();
            $ticket = $this->ticketService->change($ticket, $fields);
            $this->flashService->flash(__('messages.ticket.' . Ticket::STATUSES[$fields['status']]), 'info');

            $redirectAction = (($fields['redirect'] == 'projects') ? 'project_' : '') . 'ticket';
            return $this->ticketService->redirect($redirectAction, $ticket);
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
    }

    /**
     * Convert the ticket to task.
     */
    public function convert(ConvertTicketRequest $request, Ticket $ticket)
    {
        try {
            $fields = $request->validated();
            $task = $this->ticketService->convert($ticket);      
            $this->flashService->flash(__('messages.task.create'), 'info');

            $redirectAction = (($fields['redirect'] == 'projects') ? 'project_' : '') . 'task';
            $taskService = new TaskService(new ProjectUserService, new FlashService);
            return $taskService->redirect($redirectAction, $task);
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
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
