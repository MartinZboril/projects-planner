<?php

namespace App\Http\Controllers\Project;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\{Project, Ticket};
use App\Services\Data\TicketService;
use App\Traits\FlashTrait;

class ProjectTicketMarkController extends Controller
{
    use FlashTrait;

    public function __construct(private TicketService $ticketService)
    {
    }

    /**
     * Mark selected ticket.
     */
    public function __invoke(Project $project, Ticket $ticket): RedirectResponse
    {
        try {
            $ticket = $this->ticketService->handleMark($ticket);
            $this->flash(__('messages.ticket.' . ($ticket->is_marked ? 'mark' : 'unmark')), 'info');
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
        return redirect()->route('projects.tickets.show', ['project' => $project, 'ticket' => $ticket]);
    } 
}
