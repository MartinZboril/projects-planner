<?php

namespace App\Http\Controllers\Task;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use App\Models\Task;
use App\Http\Controllers\Controller;
use App\Services\Data\TaskService;

class TaskPauseController extends Controller
{
    public function __construct(private TaskService $taskService)
    {
    }

    /**
     * Start/stop working on the task.
     */
    public function __invoke(Task $task): JsonResponse
    {
        try {
            $task = $this->taskService->handlePause($task);
        } catch (Exception $exception) {
            Log::error($exception);
        }
        return response()->json([
            'message' => __('messages.task.' . ($task->paused ? 'stop' : 'resume')),
            'task' => $task,
        ]);
    }
}
