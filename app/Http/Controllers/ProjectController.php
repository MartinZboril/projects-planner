<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Client;
use App\Models\User;
use App\Models\ProjectUser;
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
