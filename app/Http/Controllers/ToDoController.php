<?php

namespace App\Http\Controllers;

use App\Models\ToDo;
use App\Models\Task;
use App\Services\ToDoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ToDoController extends Controller
{  
    protected $todoService;

    public function __construct(ToDoService $todoService)
    {
        $this->middleware('auth');
        $this->todoService = $todoService;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Task $task)
    {
        return view('todos.create', ['task' => $task]);
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
            'deadline' => ['required', 'date'],
            'description' => ['max:65553'],
            'redirect' => ['in:tasks,projects'],
        ]);

        if ($validator->fails()) {
            return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
        }

        $todo = $this->todoService->store($request);
        $this->todoService->flash('create');

        $redirectAction = (($request->redirect == 'projects') ? 'project_' : '') . 'task';
        return $this->todoService->redirect($redirectAction, $todo);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ToDo  $todo
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task, ToDo $todo)
    {
        return view('todos.edit', ['task' => $task, 'todo' => $todo]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ToDo  $todo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ToDo $todo)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'deadline' => ['required', 'date'],
            'is_finished' => ['boolean'],
            'description' => ['max:65553'],
            'redirect' => ['in:tasks,projects'],
        ]);

        if ($validator->fails()) {
            return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
        }

        $todo = $this->todoService->update($todo, $request);
        $this->todoService->flash('update');

        $redirectAction = (($request->redirect == 'projects') ? 'project_' : '') . 'task';
        return $this->todoService->redirect($redirectAction, $todo);
    }

    /**
     * Check the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ToDo  $todo
     * @return \Illuminate\Http\Response
     */
    public function check(Request $request, ToDo $todo)
    {
        $validator = Validator::make($request->all(), [
            'action' => ['boolean'],
            'redirect' => ['in:tasks,projects'],
        ]);

        if ($validator->fails()) {
            return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
        }

        $todo = $this->todoService->check($todo, $request);
        $flashAction = ($todo->is_checked) ? 'finish' : 'return';
        $this->todoService->flash($flashAction);

        $redirectAction = (($request->redirect == 'projects') ? 'project_' : '') . 'task';
        return $this->todoService->redirect($redirectAction, $todo);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ToDo  $toDo
     * @return \Illuminate\Http\Response
     */
    public function destroy(ToDo $toDo)
    {
        //
    }
}
