<?php

namespace App\Http\Controllers\Ticket;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ticket\ConvertTicketRequest;
use App\Models\Task;
use App\Models\Ticket;
use App\Services\Data\TaskService;
use App\Services\Data\TicketService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class TicketConvertTicketToTaskController extends Controller
{
    public function __construct(
        private TicketService $ticketService,
        private TaskService $taskService
    ) {
    }

    /**
     * Convert the ticket to new task.
     */
    public function __invoke(ConvertTicketRequest $request, Ticket $ticket): JsonResponse
    {
        try {
            $task = $this->taskService->handleSave(new Task, $request->validated() + [
                'project_id' => $ticket->project_id,
                'author_id' => $ticket->reporter_id,
                'user_id' => $ticket->assignee_id,
                'name' => $ticket->subject,
                'started_at' => $ticket->dued_at,
                'dued_at' => $ticket->dued_at,
                'description' => $ticket->message,
                'is_stopped' => 0,
                'is_returned' => 0,
                'ticket_id' => $ticket->id,
            ]);
            $this->ticketService->handleConvert($ticket);
        } catch (Exception $exception) {
            Log::error($exception);
        }

        return response()->json([
            'redirect' => route('tasks.show', $task),
        ]);
    }
}
