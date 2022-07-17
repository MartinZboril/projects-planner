<?php

namespace App\Http\Controllers;

use App\Models\ToDo;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ToDoController extends Controller
{
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
    public function store(Request $request, Task $task)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'deadline' => ['required', 'date'],
            'description' => ['max:65553'],
        ]);

        if ($validator->fails()) {
            return redirect()
                    ->route('todos.create', ['task' => $task])
                    ->withErrors($validator)
                    ->withInput();
        }

        $todo = new ToDo();

        $todo->name = $request->name;
        $todo->task_id = $task->id;
        $todo->deadline = $request->deadline;
        $todo->description = $request->description;

        $todo->save();

        Session::flash('message', 'ToDo was created!');
        Session::flash('type', 'info');

        return redirect()->route('tasks.detail', ['task' => $task]);
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
    public function update(Request $request, Task $task, ToDo $todo)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'deadline' => ['required', 'date'],
            'description' => ['required'],
        ]);

        if ($validator->fails()) {
            return redirect()
                    ->route('todos.edit', ['task' => $task, 'todo' => $todo])
                    ->withErrors($validator)
                    ->withInput();
        }

        ToDo::where('id', $todo->id)
                    ->update([
                        'name' => $request->name,
                        'deadline' => $request->deadline,
                        'description' => $request->description,
                    ]);

        Session::flash('message', 'ToDo was updated!');
        Session::flash('type', 'info');

        return redirect()->route('tasks.detail', ['task' => $task]);
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
