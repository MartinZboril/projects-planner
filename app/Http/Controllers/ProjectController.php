<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Client;
use App\Models\User;
use App\Models\ProjectUser;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
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
        $projects = Project::all();

        return view('projects.index', ['projects' => $projects]);

    }

    /**
     * Display the specified resource of project tasks.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function tasks(Project $project)
    {
        return view('projects.tasks', ['project' => $project]);
    }

    /**
     * Display the specified resource of project tasks with kanban board.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function kanban(Project $project)
    {
        return view('projects.kanban', ['project' => $project]);
    }

    
    /**
     * Display the specified resource of project milestones.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function milestones(Project $project)
    {
        return view('projects.milestones', ['project' => $project]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('projects.create', ['clients' => Client::all(), 'users' => User::all()]);
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
            'client_id' => ['required', 'integer', 'exists:clients,id'],
            'team' => ['required', 'array'],
            'due_date' => ['required', 'date'],
            'estimated_hours' => ['required', 'date'],
            'estimated_hours' => ['required', 'integer', 'min:0'],
            'budget' => ['required', 'integer', 'min:0'],
            'description' => ['required'],
        ]);

        if ($validator->fails()) {
            return redirect()
                    ->route('projects.create')
                    ->withErrors($validator)
                    ->withInput();
        }

        $project = new Project();

        $project->name = $request->name;
        $project->client_id = $request->client_id;
        $project->start_date = $request->start_date;
        $project->due_date = $request->due_date;
        $project->estimated_hours = $request->estimated_hours;
        $project->budget = $request->budget;
        $project->description = $request->description;

        $project->save();

        $team = $request->team;

        foreach ($team as $user) {
            $projectUser = new ProjectUser;

            $projectUser->project_id = $project->id;
            $projectUser->user_id = $user;

            $projectUser->save();
        }

        Session::flash('message', 'Project was created!');
        Session::flash('type', 'info');

        return ($request->create_and_close) ? redirect()->route('projects.index') : redirect()->route('projects.detail', ['project' => $project]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function detail(Project $project)
    {
        return view('projects.detail', ['project' => $project]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        return view('projects.edit', ['project' => $project, 'clients' => Client::all(), 'users' => User::all()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'client_id' => ['required', 'integer', 'exists:clients,id'],
            'team' => ['required', 'array'],
            'due_date' => ['required', 'date'],
            'estimated_hours' => ['required', 'date'],
            'estimated_hours' => ['required', 'integer', 'min:0'],
            'budget' => ['required', 'integer', 'min:0'],
            'description' => ['required'],
        ]);

        if ($validator->fails()) {
            return redirect()
                    ->route('projects.edit', ['project' => $project->id])
                    ->withErrors($validator)
                    ->withInput();
        }

        Project::where('id', $project->id)
                    ->update([
                        'name' => $request->name,
                        'client_id' => $request->client_id,
                        'start_date' => $request->start_date,
                        'due_date' => $request->due_date,
                        'estimated_hours' => $request->estimated_hours,
                        'budget' => $request->budget,
                        'description' => $request->description,
                    ]);

        $project = Project::find($project->id);

        ProjectUser::where('project_id', $project->id)->delete();

        $team = $request->team;

        foreach ($team as $user) {
            $projectUser = new ProjectUser;

            $projectUser->project_id = $project->id;
            $projectUser->user_id = $user;

            $projectUser->save();
        }

        Session::flash('message', 'Project was updated!');
        Session::flash('type', 'info');

        return ($request->save_and_close) ? redirect()->route('projects.index') : redirect()->route('projects.detail', ['project' => $project->id]);
    }

    /**
     * Start working on the task.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function start(Request $request, Project $project, Task $task)
    {
        Task::where('id', $task->id)
                    ->update([
                        'status_id' => 2,
                        'is_returned' => false,
                    ]);

        Session::flash('message', 'Start working on Task!');
        Session::flash('type', 'info');

        return redirect()->route('projects.kanban', ['project' => $task->project->id]);
    }

    /**
     * Complete working on the task.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function complete(Request $request, Project $project, Task $task)
    {
        Task::where('id', $task->id)
                    ->update([
                        'status_id' => 3,
                    ]);

        Session::flash('message', 'Task was completed!');
        Session::flash('type', 'success');

        return redirect()->route('projects.kanban', ['project' => $task->project->id]);
    }

    /**
     * Stop working on the task.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function stop(Request $request, Project $project, Task $task)
    {
        Task::where('id', $task->id)
                    ->update([
                        'is_stopped' => true,
                    ]);

        Session::flash('message', 'Task was stopped!');
        Session::flash('type', 'danger');

        return redirect()->route('projects.kanban', ['project' => $task->project->id]);
    }

    /**
     * Resume working on the task.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function resume(Request $request, Project $project, Task $task)
    {
        Task::where('id', $task->id)
                    ->update([
                        'is_stopped' => false,
                    ]);

        Session::flash('message', 'Task was resumed!');
        Session::flash('type', 'info');

        return redirect()->route('projects.kanban', ['project' => $task->project->id]);
    }

    /**
     * Return working on the task.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function return(Request $request, Project $project, Task $task)
    {
        Task::where('id', $task->id)
                    ->update([
                        'is_returned' => true,
                        'status_id' => 1,
                    ]);

        Session::flash('message', 'Task was returned!');
        Session::flash('type', 'danger');

        return redirect()->route('projects.kanban', ['project' => $task->project->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        //
    }
}
