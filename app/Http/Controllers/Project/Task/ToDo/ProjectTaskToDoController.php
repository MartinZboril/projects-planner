<?php

namespace App\Http\Controllers\Project\Task\ToDo;

use Exception;
use Illuminate\View\View;
use App\Traits\FlashTrait;
use Illuminate\Http\JsonResponse;
use App\Services\Data\ToDoService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\{Project, Task, ToDo};
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\ToDo\DestroyToDoRequest;
use App\Http\Requests\ToDo\{StoreToDoRequest, UpdateToDoRequest};

class ProjectTaskToDoController extends Controller
{
    use FlashTrait;

    public function __construct(
        private ToDoService $toDoService
    ) {}

    /**
     * Show the form for creating a new todo.
     */
    public function create(Project $project, Task $task): View
    {
        return view('projects.tasks.todos.create', ['project' => $project, 'task' => $task, 'todo' => new ToDo]);
    }

    /**
     * Store a newly created todo in storage.
     */
    public function store(StoreToDoRequest $request, Project $project, Task $task): RedirectResponse
    {
        try {
            $this->toDoService->handleSave(new ToDo, $request->validated(), $task);
            $this->flash(__('messages.todo.create'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
        return redirect()->route('projects.tasks.show', ['project' => $project, 'task' => $task]);
    }

    /**
     * Show the form for editing the todo.
=    */
    public function edit(Project $project, Task $task, ToDo $todo): View
    {
        return view('projects.tasks.todos.edit', ['project' => $project, 'todo' => $todo]);
    }

    /**
     * Update the todo in storage.
     */
    public function update(UpdateToDoRequest $request, Project $project, Task $task, ToDo $todo): RedirectResponse
    {
        try {
            $this->toDoService->handleSave($todo, $request->validated(), $task);
            $this->flash(__('messages.todo.update'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
        return redirect()->route('projects.tasks.show', ['project' => $project, 'task' => $task]);
    }

    /**
     * Remove the todo from storage.
     */
    public function destroy(DestroyToDoRequest $request, Project $project, Task $task, ToDo $todo): JsonResponse|RedirectResponse
    {
        try {
            $todo->delete();
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }

        if ($request->redirect ?? false) {
            $this->flash(__('messages.todo.delete'), 'danger');
            return redirect()->route('projects.tasks.show', ['project' => $project, 'task' => $task]);
        }

        return response()->json([
            'message' => __('messages.todo.delete'),
            'todo' => $todo,
        ]);
    }
}
