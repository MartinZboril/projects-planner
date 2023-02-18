<?php

namespace App\Http\Controllers\Ticket;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\Ticket\ConvertTicketRequest;
use App\Models\{Task, Ticket};
use App\Traits\FlashTrait;
use App\Services\Data\{ProjectUserService, TaskService, TicketService};

class TicketConvertTicketToTaskController extends Controller
{
    use FlashTrait;

    public function __construct(private TicketService $ticketService, private TaskService $taskService)
    {
    }

    /**
     * Convert the ticket to new task.
     */
    public function __invoke(ConvertTicketRequest $request, Ticket $ticket): RedirectResponse
    {
        try {
            $task = $this->taskService->handleSave(new Task, $request->safe()->merge([
                'project_id' => $ticket->project_id,
                'author_id' => $ticket->reporter_id,
                'user_id' => $ticket->assignee_id,
                'name' => $ticket->subject,
                'start_date' => $ticket->due_date,
                'due_date' => $ticket->due_date,
                'description' => $ticket->message,
            ]));
            $this->ticketService->handleConvert($ticket);      
            $this->flash(__('messages.task.create'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
        return redirect()->route('tasks.show', $task);
    }
}
