<?php

namespace App\Http\Controllers\Task;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Traits\FlashTrait;
use App\Services\Data\TaskService;

class TaskMarkController extends Controller
{
    use FlashTrait;

    public function __construct(private TaskService $taskService)
    {        
    }

    /**
     * Mark selected task.
     */
    public function __invoke(Task $task): RedirectResponse
    {
        try {
            $task = $this->taskService->handleMark($task);
            $this->flash(__('messages.task.' . ($task->is_marked ? 'mark' : 'unmark')), 'info');
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
        return redirect()->route('tasks.show', $task);
    } 
}
