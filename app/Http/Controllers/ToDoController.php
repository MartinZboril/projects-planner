<?php

namespace App\Http\Controllers;

use App\Http\Requests\ToDo\{CheckToDoRequest, StoreToDoRequest, UpdateToDoRequest};
use App\Models\{Task, ToDo};
use App\Services\ToDoService;

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
     */
    public function store(StoreToDoRequest $request)
    {
        $fields = $request->validated();
        $todo = $this->todoService->store($fields);
        $this->todoService->flash('create');

        $redirectAction = (($fields['redirect'] == 'projects') ? 'project_' : '') . 'task';
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
     */
    public function update(UpdateToDoRequest $request, ToDo $todo)
    {
        $fields = $request->validated();
        $todo = $this->todoService->update($todo, $fields);
        $this->todoService->flash('update');

        $redirectAction = (($fields['redirect'] == 'projects') ? 'project_' : '') . 'task';
        return $this->todoService->redirect($redirectAction, $todo);
    }

    /**
     * Check the specified resource in storage.
     *
     * @param  \App\Http\Requests\ToDo\CheckToDoRequest  $request
     * @param  \App\Models\ToDo  $todo
     * @return \Illuminate\Http\Response
     */
    public function check(CheckToDoRequest $request, ToDo $todo)
    {
        $fields = $request->validated();

        $todo = $this->todoService->check($todo, $fields);
        $flashAction = ($todo->is_checked) ? 'finish' : 'return';
        $this->todoService->flash($flashAction);

        $redirectAction = (($fields['redirect'] == 'projects') ? 'project_' : '') . 'task';
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
