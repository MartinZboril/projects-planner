<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use App\Services\TaskService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

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
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'project_id' => ['required', 'integer', 'exists:projects,id'],
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'start_date' => ['required', 'date'],
            'due_date' => ['required', 'date'],
            'description' => ['required'],
            'redirect' => ['in:tasks,projects'],            
        ]);

        if ($validator->fails()) {
            return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
        }

        $task = $this->taskService->store($request);
        $this->taskService->flash('create');

        $redirectAction =  (($request->redirect == 'projects') ? 'project_' : '') . (($request->create_and_close) ? 'tasks' : 'task');
        return $this->taskService->redirect($redirectAction, $task);
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
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'project_id' => ['required', 'integer', 'exists:projects,id'],
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'start_date' => ['required', 'date'],
            'due_date' => ['required', 'date'],
            'description' => ['required'],
            'redirect' => ['in:tasks,projects'],
        ]);

        if ($validator->fails()) {
            return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
        }

        $task = $this->taskService->update($task, $request);
        $this->taskService->flash('update');

        $redirectAction =  (($request->redirect == 'projects') ? 'project_' : '') . (($request->save_and_close) ? 'tasks' : 'task');
        return $this->taskService->redirect($redirectAction, $task);
    }

    /**
     * Change working on the task.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function change(Request $request, Task $task)
    {
        $validator = Validator::make($request->all(), [
            'status_id' => ['required', 'integer', 'in:1,2,3'],
            'redirect' => ['in:tasks,projects,kanban'],            
        ]);

        if ($validator->fails()) {
            return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
        }

        $task = $this->taskService->change($task, $request);
        $flashAction = match ($request->status_id) {
            1 => 'return',
            2 => 'working',
            3 => 'complete',
            default => ''
        };
        $this->taskService->flash($flashAction);

        $redirectAction =  ($request->redirect == 'kanban') ? 'kanban' : ((($request->redirect == 'projects') ? 'project_' : '') . 'task');
        return $this->taskService->redirect($redirectAction, $task);
    }

    /**
     * Strat/stop working on the task.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function pause(Request $request, Task $task)
    {
        $validator = Validator::make($request->all(), [
            'action' => ['boolean'],
            'redirect' => ['in:tasks,projects,kanban'],
        ]);
        
        if ($validator->fails()) {
            return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
        }

        $task = $this->taskService->pause($task, $request);
        $flashAction = ($task->is_stopped) ? 'stop' : 'resume';
        $this->taskService->flash($flashAction);

        $redirectAction =  ($request->redirect == 'kanban') ? 'kanban' : ((($request->redirect == 'projects') ? 'project_' : '') . 'task');
        return $this->taskService->redirect($redirectAction, $task);
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
