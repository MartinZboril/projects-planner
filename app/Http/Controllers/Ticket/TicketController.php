<?php

namespace App\Http\Controllers\Ticket;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Http\Requests\Ticket\{StoreTicketRequest, UpdateTicketRequest};
use App\Models\{Comment, Project, Ticket, User};
use App\Services\Data\TicketService;
use App\Traits\FlashTrait;

class TicketController extends Controller
{  
    use FlashTrait;

    public function __construct(private TicketService $ticketService)
    {
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
        return view('tickets.create');
    }

    /**
     * Store a newly created ticket in storage.
     */
    public function store(StoreTicketRequest $request): RedirectResponse
    {
        try {
            $ticket = $this->ticketService->handleSave(new Ticket, $request->safe());
            $this->flash(__('messages.ticket.create'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
        return $request->has('save_and_close')
            ? redirect()->route('tickets.index')
            : redirect()->route('tickets.show', $ticket);
    }

    /**
     * Display the ticket.
     */
    public function show(Ticket $ticket): View
    {
        return view('tickets.show', ['ticket' => $ticket]);
    }

    /**
     * Show the form for editing the ticket.
     */
    public function edit(Ticket $ticket): View
    {
        return view('tickets.edit', ['ticket' => $ticket]);
    }

    /**
     * Update the ticket in storage.
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket): RedirectResponse
    {
        try {
            $ticket = $this->ticketService->handleSave($ticket, $request->safe());
            $this->flash(__('messages.ticket.update'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
        return $request->has('save_and_close')
            ? redirect()->route('tickets.index')
            : redirect()->route('tickets.show', $ticket);
    }
}
