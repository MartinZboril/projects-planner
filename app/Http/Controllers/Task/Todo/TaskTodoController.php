<?php

namespace App\Http\Controllers\Task\Todo;

use App\Http\Controllers\Controller;
use App\Http\Requests\Todo\DestroyTodoRequest;
use App\Http\Requests\Todo\StoreTodoRequest;
use App\Http\Requests\Todo\UpdateTodoRequest;
use App\Models\Task;
use App\Models\Todo;
use App\Services\Data\TodoService;
use App\Traits\FlashTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class TaskTodoController extends Controller
{
    use FlashTrait;

    public function __construct(
        private TodoService $toDoService
    ) {
    }

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
    public function store(StoreTodoRequest $request, Task $task): RedirectResponse
    {
        try {
            $this->toDoService->handleSave(new Todo, $request->validated(), $task);
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
    public function edit(Task $task, Todo $todo): View
    {
        return view('tasks.todos.edit', ['todo' => $todo]);
    }

    /**
     * Update the todo in storage.
     */
    public function update(UpdateTodoRequest $request, Task $task, Todo $todo): RedirectResponse
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
    public function destroy(DestroyTodoRequest $request, Task $task, Todo $todo): JsonResponse|RedirectResponse
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
