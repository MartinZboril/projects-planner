<?php

namespace App\Http\Controllers\Ticket;

use Exception;
use App\Models\Task;
use App\Models\Ticket;
use App\Traits\FlashTrait;
use Illuminate\Http\Request;
use App\Services\Data\TaskService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\Data\TicketService;
use Illuminate\Http\RedirectResponse;
use App\Services\Data\ProjectUserService;
use App\Http\Requests\Ticket\ConvertTicketRequest;

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
