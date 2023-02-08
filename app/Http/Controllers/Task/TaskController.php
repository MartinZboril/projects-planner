<?php

namespace App\Http\Controllers\Task;

use Exception;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Task\{StoreTaskRequest, UpdateTaskRequest};
use App\Models\{Comment, Project, Task, User};
use App\Traits\FlashTrait;
use App\Services\Data\TaskService;

class TaskController extends Controller
{
    use FlashTrait;

    public function __construct(private TaskService $taskService)
    {
    }

    /**
     * Display a listing of the tasks.
     */
    public function index(): View
    {
        return view('tasks.index', ['tasks' => Task::all()]);
    }

    /**
     * Show the form for creating a new task.
     */
    public function create(): View
    {
        return view('tasks.create', ['projects' => Project::all(), 'users' => User::all(), 'task' => new Task]);
    }

    /**
     * Store a newly created task in storage.
     */
    public function store(StoreTaskRequest $request): RedirectResponse
    {
        try {
            $task = $this->taskService->handleSave(new Task, $request->safe());
            $this->flash(__('messages.task.create'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
        return $request->has('save_and_close')
            ? redirect()->route('tasks.index')
            : redirect()->route('tasks.show', $task);
    }

    /**
     * Display the task.
     */
    public function show(Task $task): View
    {
        return view('tasks.show', ['task' => $task, 'comment' => new Comment]);
    }

    /**
     * Show the form for editing the task.
     */
    public function edit(Task $task): View
    {
        return view('tasks.edit', ['task' => $task, 'projects' => Project::all(), 'users' => User::all()]);
    }

    /**
     * Update the task in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task): RedirectResponse
    {
        try {
            $task = $this->taskService->handleSave($task, $request->safe());
            $this->flash(__('messages.task.update'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
        return $request->has('save_and_close')
            ? redirect()->route('tasks.index')
            : redirect()->route('tasks.show', $task);
    }
}
