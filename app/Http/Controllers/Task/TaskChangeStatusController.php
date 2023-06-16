<?php

namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;
use App\Http\Requests\Task\ChangeTaskRequest;
use App\Models\Task;
use App\Services\Data\TaskService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class TaskChangeStatusController extends Controller
{
    public function __construct(
        private TaskService $taskService
    ) {
    }

    /**
     * Change working status on the task.
     */
    public function __invoke(ChangeTaskRequest $request, Task $task): JsonResponse
    {
        try {
            $task = $this->taskService->handleChangeStatus($task, $request->status);
        } catch (Exception $exception) {
            Log::error($exception);
        }

        return response()->json([
            'message' => __('messages.task.'.$task->status->name),
            'task' => $task,
        ]);
    }
}
