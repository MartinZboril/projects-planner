<?php

namespace App\Http\Controllers\Ticket;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Services\Data\TicketService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class TicketMarkController extends Controller
{
    public function __construct(
        private TicketService $ticketService
    ) {
    }

    /**
     * Mark selected ticket.
     */
    public function __invoke(Ticket $ticket): JsonResponse
    {
        try {
            $ticket = $this->ticketService->handleMark($ticket);
        } catch (Exception $exception) {
            Log::error($exception);
        }

        return response()->json([
            'message' => __('messages.ticket.'.($ticket->is_marked ? 'mark' : 'unmark')),
            'ticket' => $ticket,
        ]);
    }
}
