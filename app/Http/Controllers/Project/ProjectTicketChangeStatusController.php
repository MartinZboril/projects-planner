<?php

namespace App\Http\Controllers\Project;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\Ticket\ChangeTicketRequest;
use App\Models\{Ticket, Project};
use App\Traits\FlashTrait;
use App\Services\Data\TicketService;

class ProjectTicketChangeStatusController extends Controller
{
    use FlashTrait;

    public function __construct(private TicketService $ticketService)
    {
    }

    /**
     * Change working status of the ticket.
     */
    public function __invoke(ChangeTicketRequest $request, Project $project, Ticket $ticket): RedirectResponse
    {
        try {
            $ticket = $this->ticketService->handleChange($ticket, $request->status);
            $this->flash(__('messages.ticket.' . $ticket->status->name), 'info');
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
        return redirect()->route('projects.tickets.show', ['project' => $project, 'ticket' => $ticket]);
    }
}
