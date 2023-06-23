<?php

namespace App\Http\Controllers\Ticket;

use App\DataTables\TicketsDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Ticket\StoreTicketRequest;
use App\Http\Requests\Ticket\UpdateTicketRequest;
use App\Models\Ticket;
use App\Services\Data\TicketService;
use App\Traits\FlashTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class TicketController extends Controller
{
    use FlashTrait;

    public function __construct(
        private TicketService $ticketService
    ) {
    }

    /**
     * Display a listing of the tickets.
     */
    public function index(TicketsDataTable $ticketsDataTable): JsonResponse|View
    {
        return $ticketsDataTable->with([
            'view' => 'tickets',
        ])->render('tickets.index');
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
            $ticket = $this->ticketService->handleSave(new Ticket, $request->validated(), $request->file('files'));
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
            $ticket = $this->ticketService->handleSave($ticket, $request->validated());
            $this->flash(__('messages.ticket.update'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);

            return redirect()->back()->with(['error' => __('messages.error')]);
        }

        return $request->has('save_and_close')
            ? redirect()->route('tickets.index')
            : redirect()->route('tickets.show', $ticket);
    }
    
    /**
     * Remove the ticket from storage.
     */
    public function destroy(Ticket $ticket): JsonResponse
    {
        try {
            $this->ticketService->handleDelete($ticket);
        } catch (Exception $exception) {
            Log::error($exception);
        }

        return response()->json([
            'message' => __('messages.ticket.delete'),
        ]);
    }
}
