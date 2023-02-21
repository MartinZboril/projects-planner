<?php

namespace App\Http\Controllers\Task\ToDo;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Http\Requests\ToDo\{StoreToDoRequest, UpdateToDoRequest};
use App\Models\{Task, ToDo};
use App\Traits\FlashTrait;
use App\Services\Data\ToDoService;

class TaskToDoController extends Controller
{
    use FlashTrait;

    public function __construct(private ToDoService $toDoService)
    {
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
    public function store(StoreToDoRequest $request, Task $task): RedirectResponse
    {
        try {
            $this->toDoService->handleSave(new ToDo, $request->safe(), $task);
            $this->flash(__('messages.todo.create'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
        return $request->has('save_and_close')
            ? redirect()->route('tasks.index')
            : redirect()->route('tasks.show', $task);
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
            $this->toDoService->handleSave($todo, $request->safe(), $task);
            $this->flash(__('messages.todo.update'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
        return $request->has('save_and_close')
            ? redirect()->route('tasks.index')
            : redirect()->route('tasks.show', $task);
    }
}
