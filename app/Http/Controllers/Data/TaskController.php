<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Http\Requests\Task\{ChangeTaskRequest, StoreTaskRequest, PauseTaskRequest, UpdateTaskRequest};
use App\Models\{Project, Task, User};
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
     *
     * @return \Illuminate\Http\View
     */
    public function create(): View
    {
        return view('tasks.create', ['projects' => Project::all(), 'users' => User::all()]);
    }

    /**
     * Store a newly created task in storage.
     */
    public function store(StoreTaskRequest $request): RedirectResponse
    {
        try {
            $fields = $request->validated();
            $task = $this->taskService->store($fields);
            $this->flashService->flash(__('messages.task.create'), 'info');

            $redirectAction =  (($fields['redirect'] == 'projects') ? 'project_' : '') . ((isset($request->create_and_close)) ? 'tasks' : 'task');
            return $this->taskService->redirect($redirectAction, $task);
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
        return view('tasks.detail', ['task' => $task]);
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
            $fields = $request->validated();
            $task = $this->taskService->update($task, $fields);
            $this->flashService->flash(__('messages.task.update'), 'info');

            $redirectAction =  (($fields['redirect'] == 'projects') ? 'project_' : '') . ((isset($request->save_and_close)) ? 'tasks' : 'task');
            return $this->taskService->redirect($redirectAction, $task);
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
            $fields = $request->validated();
            $task = $this->taskService->change($task, $fields);
            $this->flashService->flash(__('messages.task.' . Task::STATUSES[$fields['status']]), 'info');

            $redirectAction =  ($fields['redirect'] == 'kanban') ? 'kanban' : ((($fields['redirect'] == 'projects') ? 'project_' : '') . 'task');
            return $this->taskService->redirect($redirectAction, $task);
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
            $fields = $request->validated();
            $task = $this->taskService->pause($task, $fields);
            $this->flashService->flash(__('messages.task.' . (($fields['action']) ? Task::STOP : Task::RESUME)), 'info');

            $redirectAction =  ($fields['redirect'] == 'kanban') ? 'kanban' : ((($fields['redirect'] == 'projects') ? 'project_' : '') . 'task');
            return $this->taskService->redirect($redirectAction, $task);
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
    }
}
