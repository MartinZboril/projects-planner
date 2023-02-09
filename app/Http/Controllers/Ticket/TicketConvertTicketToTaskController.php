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
            $taskService = new TaskService(new ProjectUserService);
            $task = $taskService->handleSave(new Task, $request->safe());
            $this->ticketService->handleConvert($ticket);      
            $this->flash(__('messages.task.create'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
        return redirect()->route('tasks.show', $task);
    }
}
