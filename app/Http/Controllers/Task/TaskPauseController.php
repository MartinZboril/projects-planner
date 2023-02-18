<?php

namespace App\Http\Controllers\Task;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use App\Models\Task;
use App\Http\Controllers\Controller;
use App\Traits\FlashTrait;
use App\Services\Data\TaskService;

class TaskPauseController extends Controller
{
    use FlashTrait;

    public function __construct(private TaskService $taskService)
    {
    }

    /**
     * Start/stop working on the task.
     */
    public function __invoke(Task $task): RedirectResponse
    {
        try {
            $task = $this->taskService->handlePause($task);
            $this->flash(__('messages.task.' . ($task->paused ? 'stop' : 'resume')), 'info');
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
        return redirect()->route('tasks.show', $task);
    }
}
