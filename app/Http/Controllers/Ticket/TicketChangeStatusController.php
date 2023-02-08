<?php

namespace App\Http\Controllers\Ticket;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\Ticket\ChangeTicketRequest;
use App\Models\Ticket;
use App\Traits\FlashTrait;
use App\Services\Data\TicketService;

class TicketChangeStatusController extends Controller
{
    use FlashTrait;

    public function __construct(private TicketService $ticketService)
    {
    }

    /**
     * Change working status of the ticket.
     */
    public function __invoke(ChangeTicketRequest $request, Ticket $ticket): RedirectResponse
    {
        try {
            $ticket = $this->ticketService->handleChange($ticket, $request->status);
            $this->flash(__('messages.ticket.' . $ticket->status->name), 'info');
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
        return redirect()->route('tickets.show', $ticket);
    }
}
