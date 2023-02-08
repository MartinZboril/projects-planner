<?php

namespace App\Http\Controllers\Task;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use App\Traits\FlashTrait;
use App\Models\{Task, ToDo};
use App\Services\Data\ToDoService;
use App\Http\Controllers\Controller;

class TaskToDoCheckController extends Controller
{
    use FlashTrait;

    public function __construct(private ToDoService $toDoService)
    {
    }

    /**
     * Check the todo in storage.
     */
    public function __invoke(Task $task, ToDo $todo): RedirectResponse
    {
        try {
            $todo = $this->toDoService->handleCheck($todo);
            $this->flash(__('messages.todo.' . ($todo->is_finished ? ToDo::FINISH : ToDo::RETURN)), 'info');
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
        return redirect()->route('tasks.show', $task);
    }
}
