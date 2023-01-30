<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Http\Requests\Task\{ChangeTaskRequest, MarkTaskRequest, StoreTaskRequest, PauseTaskRequest, UpdateTaskRequest};
use App\Models\{Comment, Project, Task, User};
use App\Services\FlashService;
use App\Services\Data\TaskService;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TaskController extends Controller
{
    protected $taskService;
    protected $flashService;

    public function __construct(TaskService $taskService, FlashService $flashService)
    {
        $this->middleware('auth');
        $this->taskService = $taskService;
        $this->flashService = $flashService;
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
        return view('tasks.create', ['projects' => Project::all(), 'users' => User::all(), 'task' => new Task, 'project' => null]);
    }

    /**
     * Store a newly created task in storage.
     */
    public function store(StoreTaskRequest $request): RedirectResponse
    {
        try {
            $task = $this->taskService->store($request->safe());
            $this->flashService->flash(__('messages.task.create'), 'info');

            return $this->taskService->setUpRedirect($request->redirect, $request->has('save_and_close'), $task);
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
    }

    /**
     * Display the task.
     */
    public function detail(Task $task): View
    {
        return view('tasks.detail', ['task' => $task, 'comment' => new Comment]);
    }

    /**
     * Show the form for editing the task.
     */
    public function edit(Task $task): View
    {
        return view('tasks.edit', ['task' => $task, 'projects' => Project::all(), 'users' => User::all(), 'project' => null]);
    }

    /**
     * Update the task in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task): RedirectResponse
    {
        try {
            $task = $this->taskService->update($task, $request->safe());
            $this->flashService->flash(__('messages.task.update'), 'info');

            return $this->taskService->setUpRedirect($request->redirect, $request->has('save_and_close'), $task);
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
    }

    /**
     * Change working status on the task.
     */
    public function change(ChangeTaskRequest $request, Task $task): RedirectResponse
    {
        try {
            $task = $this->taskService->change($task, $request->status);
            $this->flashService->flash(__('messages.task.' . $task->status->name), 'info');

            return redirect()->back();
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
    }

    /**
     * Start/stop working on the task.
     */
    public function pause(PauseTaskRequest $request, Task $task): RedirectResponse
    {
        try {
            $task = $this->taskService->pause($task, $request->action);
            $this->flashService->flash(__('messages.task.' . ($task->paused ? 'stop' : 'resume')), 'info');

            return redirect()->back();
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
    }

    /**
     * Mark selected task.
     */
    public function mark(MarkTaskRequest $request, Task $task): RedirectResponse
    {
        try {
            $task = $this->taskService->mark($task);
            $this->flashService->flash(__('messages.task.' . ($task->is_marked ? 'mark' : 'unmark')), 'info');

            return redirect()->back();
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
    } 
}
