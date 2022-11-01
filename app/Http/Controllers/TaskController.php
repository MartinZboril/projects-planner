<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\{ChangeTaskRequest, StoreTaskRequest, PauseTaskRequest, UpdateTaskRequest};
use App\Models\{Project, Task, User};
use App\Services\TaskService;
use Exception;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->middleware('auth');
        $this->taskService = $taskService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('tasks.index', ['tasks' => Task::all()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tasks.create', ['projects' => Project::all(), 'users' => User::all()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        try {
            $fields = $request->validated();
            $task = $this->taskService->store($fields);
            $this->taskService->flash('create');

            $redirectAction =  (($fields['redirect'] == 'projects') ? 'project_' : '') . ((isset($fields['create_and_close'])) ? 'tasks' : 'task');
            return $this->taskService->redirect($redirectAction, $task);
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function detail(Task $task)
    {
        return view('tasks.detail', ['task' => $task]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        return view('tasks.edit', ['task' => $task, 'projects' => Project::all(), 'users' => User::all()]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        try {
            $fields = $request->validated();
            $task = $this->taskService->update($task, $fields);
            $this->taskService->flash('update');

            $redirectAction =  (($fields['redirect'] == 'projects') ? 'project_' : '') . ((isset($fields['save_and_close'])) ? 'tasks' : 'task');
            return $this->taskService->redirect($redirectAction, $task);
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
    }

    /**
     * Change working on the task.
     */
    public function change(ChangeTaskRequest $request, Task $task)
    {
        try {
            $fields = $request->validated();
            $task = $this->taskService->change($task, $fields);
            $flashAction = match ($fields['status_id']) {
                '1' => 'return',
                '2' => 'working',
                '3' => 'complete',
                default => ''
            };
            $this->taskService->flash($flashAction);

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
    public function pause(PauseTaskRequest $request, Task $task)
    {
        try {
            $fields = $request->validated();
            $task = $this->taskService->pause($task, $fields);
            $flashAction = ($task->is_stopped) ? 'stop' : 'resume';
            $this->taskService->flash($flashAction);

            $redirectAction =  ($fields['redirect'] == 'kanban') ? 'kanban' : ((($fields['redirect'] == 'projects') ? 'project_' : '') . 'task');
            return $this->taskService->redirect($redirectAction, $task);
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        //
    }
}
