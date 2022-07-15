<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = Task::all();

        return view('tasks.index', ['tasks' => $tasks]);
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
        ]);

        if ($validator->fails()) {
            return redirect()
                    ->route('tasks.create')
                    ->withErrors($validator)
                    ->withInput();
        }

        $task = new Task();

        $task->name = $request->name;
        $task->project_id = $request->project_id;
        $task->status_id = 1;
        $task->author_id = Auth::id();
        $task->user_id = $request->user_id;
        $task->start_date = $request->start_date;
        $task->due_date = $request->due_date;
        $task->description = $request->description;

        $task->save();

        Session::flash('message', 'Task was created!');
        Session::flash('type', 'info');

        return ($request->create_and_close) ? redirect()->route('tasks.index') : redirect()->route('tasks.detail', ['task' => $task]);
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
        ]);

        if ($validator->fails()) {
            return redirect()
                    ->route('tasks.edit', ['task' => $task->id])
                    ->withErrors($validator)
                    ->withInput();
        }

        Task::where('id', $task->id)
                    ->update([
                        'name' => $request->name,
                        'project_id' => $request->project_id,
                        'user_id' => $request->user_id,
                        'start_date' => $request->start_date,
                        'due_date' => $request->due_date,
                        'description' => $request->description,
                    ]);

        Session::flash('message', 'Task was updated!');
        Session::flash('type', 'info');

        return ($request->save_and_close) ? redirect()->route('tasks.index') : redirect()->route('tasks.detail', ['task' => $task->id]);
    }

    /**
     * Start working on the task.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function start(Request $request, Task $task)
    {
        Task::where('id', $task->id)
                    ->update([
                        'status_id' => 2,
                    ]);

        Session::flash('message', 'Start working on Task!');
        Session::flash('type', 'info');

        return redirect()->route('tasks.detail', ['task' => $task->id]);
    }

    /**
     * Complete working on the task.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function complete(Request $request, Task $task)
    {
        Task::where('id', $task->id)
                    ->update([
                        'status_id' => 3,
                    ]);

        Session::flash('message', 'Task was completed!');
        Session::flash('type', 'success');

        return redirect()->route('tasks.detail', ['task' => $task->id]);
    }

    /**
     * Stop working on the task.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function stop(Request $request, Task $task)
    {
        Task::where('id', $task->id)
                    ->update([
                        'is_stopped' => true,
                    ]);

        Session::flash('message', 'Task was stopped!');
        Session::flash('type', 'danger');

        return redirect()->route('tasks.detail', ['task' => $task->id]);
    }

    /**
     * Resume working on the task.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function resume(Request $request, Task $task)
    {
        Task::where('id', $task->id)
                    ->update([
                        'is_stopped' => false,
                    ]);

        Session::flash('message', 'Task was resumed!');
        Session::flash('type', 'info');

        return redirect()->route('tasks.detail', ['task' => $task->id]);
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
