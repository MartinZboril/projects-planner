<?php

namespace App\Http\Controllers\Project;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Http\Requests\ToDo\{StoreToDoRequest, UpdateToDoRequest};
use App\Models\{Project, Task, ToDo};
use App\Traits\FlashTrait;
use App\Services\Data\ToDoService;

class ProjectTaskToDoController extends Controller
{
    use FlashTrait;

    public function __construct(private ToDoService $toDoService)
    {
    }

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
            $this->toDoService->handleSave(new ToDo, $request->safe(), $task);
            $this->flash(__('messages.todo.create'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
        return $request->has('save_and_close')
            ? redirect()->route('projects.tasks.index', $project)
            : redirect()->route('projects.tasks.show', ['project' => $project, 'task' => $task]);
    }

    /**
     * Show the form for editing the todo.
=    */
    public function edit(Project $project, Task $task, ToDo $todo): View
    {
        return view('projects.tasks.todos.edit', ['project' => $project, 'task' => $task, 'todo' => $todo]);
    }

    /**
     * Update the todo in storage.
     */
    public function update(UpdateToDoRequest $request, Project $project, Task $task, ToDo $todo): RedirectResponse
    {
        try {
            $this->toDoService->handleSave($todo, $request->safe(), $task);
            $this->flash(__('messages.todo.update'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
        return $request->has('save_and_close')
            ? redirect()->route('projects.tasks.index', $project)
            : redirect()->route('projects.tasks.show', ['project' => $project, 'task' => $task]);
    }
}
