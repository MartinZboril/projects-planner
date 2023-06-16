<?php

namespace App\Http\Controllers\Ticket;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ticket\ChangeTicketRequest;
use App\Models\Ticket;
use App\Services\Data\TicketService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class TicketChangeStatusController extends Controller
{
    public function __construct(
        private TicketService $ticketService
    ) {
    }

    /**
     * Change working status of the ticket.
     */
    public function __invoke(ChangeTicketRequest $request, Ticket $ticket): JsonResponse
    {
        try {
            $ticket = $this->ticketService->handleChange($ticket, $request->status);
        } catch (Exception $exception) {
            Log::error($exception);
        }

        return response()->json([
            'message' => __('messages.ticket.'.$ticket->status->name),
            'ticket' => $ticket,
        ]);
    }
}
