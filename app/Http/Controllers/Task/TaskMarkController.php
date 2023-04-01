<?php

namespace App\Http\Controllers\Task;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Services\Data\TaskService;

class TaskMarkController extends Controller
{
    public function __construct(private TaskService $taskService)
    {        
    }

    /**
     * Mark selected task.
     */
    public function __invoke(Task $task): JsonResponse
    {
        try {
            $task = $this->taskService->handleMark($task);
        } catch (Exception $exception) {
            Log::error($exception);
        }
        return response()->json([
            'message' => __('messages.task.' . ($task->is_marked ? 'mark' : 'unmark')),
            'task' => $task,
        ]);
    } 
}
