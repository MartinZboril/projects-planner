<?php

namespace App\Http\Controllers\Project\Task;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\{Project, Task};
use App\Traits\FlashTrait;
use App\Services\Data\TaskService;

class ProjectTaskMarkController extends Controller
{
    use FlashTrait;

    public function __construct(private TaskService $taskService)
    {        
    }

    /**
     * Mark selected task.
     */
    public function __invoke(Project $project, Task $task): RedirectResponse
    {
        try {
            $task = $this->taskService->handleMark($task);
            $this->flash(__('messages.task.' . ($task->is_marked ? 'mark' : 'unmark')), 'info');
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
        return redirect()->route('projects.tasks.show', ['project' => $project, 'task' => $task]);
    } 
}

