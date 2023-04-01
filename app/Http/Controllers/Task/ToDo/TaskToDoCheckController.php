<?php

namespace App\Http\Controllers\Task\ToDo;

use Exception;
use App\Models\{Task, ToDo};
use App\Services\Data\ToDoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class TaskToDoCheckController extends Controller
{
    public function __construct(private ToDoService $toDoService)
    {
    }

    /**
     * Check the todo in storage.
     */
    public function __invoke(Task $task, ToDo $todo): JsonResponse
    {
        try {
            $todo = $this->toDoService->handleCheck($todo);
        } catch (Exception $exception) {
            Log::error($exception);
        }
        return response()->json([
            'message' => __('messages.todo.' . ($todo->is_finished ? ToDo::FINISH : ToDo::RETURN)),
            'todo' => $todo,
        ]);
    }
}
