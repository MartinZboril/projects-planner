<?php

namespace App\Http\Controllers\Project\Ticket;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Http\Requests\Ticket\{StoreTicketRequest, UpdateTicketRequest};
use App\Models\{Comment, Project, Ticket};
use App\Traits\FlashTrait;
use App\Services\Data\TicketService;

class ProjectTicketController extends Controller
{
    use FlashTrait;

    public function __construct(private TicketService $ticketService)
    {
    }

    /**
     * Display the tickets of project.
     */
    public function index(Project $project): View
    {
        return view('projects.tickets.index', ['project' => $project]);
    }

    /**
     * Show the form for creating a new ticket of project.
     */
    public function create(Project $project): View
    {
        return view('projects.tickets.create', ['project' => $project]);
    }

    /**
     * Store a newly created ticket in storage.
     */
    public function store(StoreTicketRequest $request, Project $project): RedirectResponse
    {
        try {
            $ticket = $this->ticketService->handleSave(new Ticket, $request->validated());
            $this->flash(__('messages.ticket.create'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
        return $request->has('save_and_close')
            ? redirect()->route('projects.tickets.index', $project)
            : redirect()->route('projects.tickets.show', ['project' => $project, 'ticket' => $ticket]);
    }

    /**
     * Display the ticket of project.
     */
    public function show(Project $project, Ticket $ticket): View
    {
        return view('projects.tickets.show', ['project' => $project, 'ticket' => $ticket]);
    }

    /**
     * Show the form for editing the ticket of project.
     */
    public function edit(Project $project, Ticket $ticket): View
    {
        return view('projects.tickets.edit', ['project' => $project, 'ticket' => $ticket]);
    }

    /**
     * Update the ticket in storage.
     */
    public function update(UpdateTicketRequest $request, Project $project, Ticket $ticket): RedirectResponse
    {
        try {
            $ticket = $this->ticketService->handleSave($ticket, $request->validated());
            $this->flash(__('messages.ticket.update'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
        return $request->has('save_and_close')
            ? redirect()->route('projects.tickets.index', $project)
            : redirect()->route('projects.tickets.show', ['project' => $project, 'ticket' => $ticket]);
    }
}
