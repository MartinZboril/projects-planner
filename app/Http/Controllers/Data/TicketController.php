<?php

namespace App\Http\Controllers\Data;

use Exception;
use Illuminate\View\View;
use App\Services\FlashService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Models\{Comment, Project, Ticket, Task, User};
use App\Services\Data\{TaskService, TicketService, ProjectUserService};
use App\Http\Requests\Ticket\{ConvertTicketRequest, ChangeTicketRequest, StoreTicketRequest, UpdateTicketRequest};

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
     * Display a listing of the tickets.
     */
    public function index(): View
    {
        return view('tickets.index', ['tickets' => Ticket::all()]);
    }

    /**
     * Show the form for creating a new ticket.
     */
    public function create(): View
    {
        return view('tickets.create', ['projects' => Project::all(), 'users' => User::all(), 'project' => null, 'ticket' => new Ticket]);
    }

    /**
     * Store a newly created ticket in storage.
     */
    public function store(StoreTicketRequest $request): RedirectResponse
    {
        try {
            $ticket = $this->ticketService->save(new Ticket, $request->safe());
            $this->flashService->flash(__('messages.ticket.create'), 'info');
            return $this->ticketService->setUpRedirect($request->redirect, $request->has('save_and_close'), $ticket);
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
    }

    /**
     * Display the ticket.
     */
    public function detail(Ticket $ticket): View
    {
        return view('tickets.detail', ['ticket' => $ticket, 'comment' => new Comment]);
    }

    /**
     * Show the form for editing the ticket.
     */
    public function edit(Ticket $ticket): View
    {
        return view('tickets.edit', ['ticket' => $ticket, 'projects' => Project::all(), 'users' => User::all(), 'project' => null]);
    }

    /**
     * Update the ticket in storage.
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket): RedirectResponse
    {
        try {
            $ticket = $this->ticketService->save($ticket, $request->safe());
            $this->flashService->flash(__('messages.ticket.update'), 'info');
            return $this->ticketService->setUpRedirect($request->redirect, $request->has('save_and_close'), $ticket);
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
    }

    /**
     * Change working status of the ticket.
     */
    public function change(ChangeTicketRequest $request, Ticket $ticket): RedirectResponse
    {
        try {
            $ticket = $this->ticketService->change($ticket, $request->status);
            $this->flashService->flash(__('messages.ticket.' . $ticket->status->name), 'info');
            return redirect()->back();
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
    }

    /**
     * Convert the ticket to new task.
     */
    public function convert(ConvertTicketRequest $request, Ticket $ticket): RedirectResponse
    {
        try {
            $taskService = new TaskService(new ProjectUserService, new FlashService);
            $task = $taskService->save(new Task, $request->safe());
            $this->ticketService->convert($ticket);      
            $this->flashService->flash(__('messages.task.create'), 'info');
            return $taskService->setUpRedirect($request->redirect, false, $task);
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
    }

    /**
     * Mark selected ticket.
     */
    public function mark(Ticket $ticket): RedirectResponse
    {
        try {
            $ticket = $this->ticketService->mark($ticket);
            $this->flashService->flash(__('messages.ticket.' . ($ticket->is_marked ? 'mark' : 'unmark')), 'info');
            return redirect()->back();
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
    } 
}
