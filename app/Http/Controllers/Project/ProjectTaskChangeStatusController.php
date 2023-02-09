<?php

namespace App\Http\Controllers\Project;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\{Project, Task};
use App\Traits\FlashTrait;
use App\Services\Data\TaskService;
use App\Http\Requests\Task\ChangeTaskRequest;

class ProjectTaskChangeStatusController extends Controller
{
    use FlashTrait;

    public function __construct(private TaskService $taskService)
    {
    }

    /**
     * Change working status on the task.
     */
    public function __invoke(ChangeTaskRequest $request, Project $project, Task $task): RedirectResponse
    {
        try {
            $task = $this->taskService->handleChangeStatus($task, $request->status);
            $this->flash(__('messages.task.' . $task->status->name), 'info');
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
        return redirect()->route('projects.tasks.show', ['project' => $project, 'task' => $task]);
    }
}
