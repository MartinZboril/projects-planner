<?php

namespace App\Http\Controllers\Task\Todo;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Todo;
use App\Services\Data\TodoService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class TaskTodoCheckController extends Controller
{
    public function __construct(
        private TodoService $toDoService
    ) {
    }

    /**
     * Check the todo in storage.
     */
    public function __invoke(Task $task, Todo $todo): JsonResponse
    {
        try {
            $todo = $this->toDoService->handleCheck($todo);
        } catch (Exception $exception) {
            Log::error($exception);
        }

        return response()->json([
            'message' => __('messages.todo.'.($todo->is_finished ? Todo::FINISH : Todo::RETURN)),
            'todo' => $todo,
        ]);
    }
}
