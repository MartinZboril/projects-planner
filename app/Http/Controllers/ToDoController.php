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
    public function store(Request $request)
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
        $todo->task_id = $request->task_id;
        $todo->deadline = $request->deadline;
        $todo->description = $request->description;

        $todo->save();

        Session::flash('message', 'ToDo was created!');
        Session::flash('type', 'info');

        if($request->project_create) {
            return redirect()->route('projects.task.detail', ['project' => $todo->task->project, 'task' => $todo->task]);
        }

        return redirect()->route('tasks.detail', ['task' => $todo->task]);
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
        ]);

        if ($validator->fails()) {
            return redirect()
                    ->route('todos.edit', ['task' => $todo->task, 'todo' => $todo])
                    ->withErrors($validator)
                    ->withInput();
        }

        ToDo::where('id', $todo->id)
                    ->update([
                        'name' => $request->name,
                        'task_id' => $request->task_id,
                        'deadline' => $request->deadline,
                        'is_finished' => $request->is_finished,
                        'description' => $request->description,
                    ]);

        Session::flash('message', 'ToDo was updated!');
        Session::flash('type', 'info');

        if($request->project_save) {
            return redirect()->route('projects.task.detail', ['project' => $todo->task->project, 'task' => $todo->task]);
        }

        return redirect()->route('tasks.detail', ['task' => $todo->task]);
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
        if($todo->is_finished) {
            ToDo::where('id', $todo->id)
                ->update([
                    'is_finished' => false,
                ]);

            Session::flash('message', 'ToDo was returned!');
        } else {
            ToDo::where('id', $todo->id)
                ->update([
                    'is_finished' => true,
                ]);

            Session::flash('message', 'ToDo was finished!');
        }

        Session::flash('type', 'info');

        if($request->redirect == "project") {
            return redirect()->route('projects.task.detail', ['project' => $todo->task->project, 'task' => $todo->task]);
        }

        return redirect()->route('tasks.detail', ['task' => $todo->task]);
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
