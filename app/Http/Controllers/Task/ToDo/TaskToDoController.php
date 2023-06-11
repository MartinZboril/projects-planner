<?php

namespace App\Http\Controllers\Task\ToDo;

use Exception;
use Illuminate\View\View;
use App\Traits\FlashTrait;
use App\Models\{Task, ToDo};
use Illuminate\Http\JsonResponse;
use App\Services\Data\ToDoService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\ToDo\DestroyToDoRequest;
use App\Http\Requests\ToDo\{StoreToDoRequest, UpdateToDoRequest};

class TaskToDoController extends Controller
{
    use FlashTrait;

    public function __construct(
        private ToDoService $toDoService
    ) {}

    /**
     * Show the form for creating a new todo.
     */
    public function create(Task $task): View
    {
        return view('tasks.todos.create', ['task' => $task]);
    }

    /**
     * Store a newly created todo in storage.
     */
    public function store(StoreToDoRequest $request, Task $task): RedirectResponse
    {
        try {
            $this->toDoService->handleSave(new ToDo, $request->validated(), $task);
            $this->flash(__('messages.todo.create'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
        return redirect()->route('tasks.show', $task);
    }

    /**
     * Show the form for editing the todo.
=    */
    public function edit(Task $task, ToDo $todo): View
    {
        return view('tasks.todos.edit', ['todo' => $todo]);
    }

    /**
     * Update the todo in storage.
     */
    public function update(UpdateToDoRequest $request, Task $task, ToDo $todo): RedirectResponse
    {
        try {
            $this->toDoService->handleSave($todo, $request->validated(), $task);
            $this->flash(__('messages.todo.update'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
        return redirect()->route('tasks.show', $task);
    }

    /**
     * Remove the todo from storage.
     */
    public function destroy(DestroyToDoRequest $request,Task $task, ToDo $todo): JsonResponse|RedirectResponse
    {
        try {
            $todo->delete();
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }

        if ($request->redirect ?? false) {
            $this->flash(__('messages.todo.delete'), 'danger');
            return redirect()->route('tasks.show', $task);
        }

        return response()->json([
            'message' => __('messages.todo.delete'),
            'todo' => $todo,
        ]);
    }
}
