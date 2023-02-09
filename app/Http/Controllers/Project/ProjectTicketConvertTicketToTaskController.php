<?php

namespace App\Http\Controllers\Project;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\Ticket\ConvertTicketRequest;
use App\Models\{Project, Task, Ticket};
use App\Traits\FlashTrait;
use App\Services\Data\{ProjectUserService, TaskService, TicketService};

class ProjectTicketConvertTicketToTaskController extends Controller
{
    use FlashTrait;

    public function __construct(private TicketService $ticketService, private TaskService $taskService)
    {
    }

    /**
     * Convert the ticket to new task.
     */
    public function __invoke(ConvertTicketRequest $request, Project $project, Ticket $ticket): RedirectResponse
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
        return redirect()->route('projects.tasks.show', ['project' => $project, 'task' => $task]);
    }
}

