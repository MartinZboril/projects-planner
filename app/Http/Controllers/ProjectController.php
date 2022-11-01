<?php

namespace App\Http\Controllers;

use App\Http\Requests\Project\{StoreProjectRequest, UpdateProjectRequest};
use App\Models\{Client, Milestone, Project, Task, Ticket, ToDo, User};
use App\Services\ProjectService;
use Exception;
use Illuminate\Support\Facades\Log;

class ProjectController extends Controller
{
    protected $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->middleware('auth');
        $this->projectService = $projectService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('projects.index', ['projects' => Project::all()]);
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
     * Display the specified resource of project timesheets.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function timesheets(Project $project)
    {
        return view('projects.timesheets', ['project' => $project]);
    }

    /**
     * Display the specified resource of project tickets.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function tickets(Project $project)
    {
        return view('projects.tickets', ['project' => $project]);
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
     */
    public function store(StoreProjectRequest $request)
    {
        try {
            $fields = $request->validated();
            $project = $this->projectService->store($fields);
            $this->projectService->flash('create');

            $redirectAction = isset($fields['create_and_close']) ? 'projects' : 'project';
            return $this->projectService->redirect($redirectAction, $project);
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
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
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        try {
            $fields = $request->validated();
            $project = $this->projectService->update($project, $fields);
            $this->projectService->flash('update');

            $redirectAction = isset($fields['save_and_close']) ? 'projects' : 'project';
            return $this->projectService->redirect($redirectAction, $project);
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        } 
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function createTask(Project $project)
    {
        return view('projects.task.create', ['project' => $project, 'milestones' => Milestone::where('project_id', $project->id)->get(), 'users' => User::all()]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function detailTask(Project $project, Task $task)
    {
        return view('projects.task.detail', ['project' => $project, 'task' => $task, 'milestones' => Milestone::where('project_id', $project->id)->get(), 'users' => User::all()]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function editTask(Project $project, Task $task)
    {
        return view('projects.task.edit', ['project' => $project, 'task' => $task, 'milestones' => Milestone::where('project_id', $project->id)->get(), 'users' => User::all()]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function createToDo(Project $project, Task $task)
    {
        return view('projects.todo.create', ['project' => $project, 'task' => $task]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @param  \App\Models\Task  $task
     * @param  \App\Models\ToDo  $todo
     * @return \Illuminate\Http\Response
     */
    public function editToDo(Project $project, Task $task, ToDo $todo)
    {
        return view('projects.todo.edit', ['project' => $project, 'task' => $task, 'todo' => $todo]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function createTicket(Project $project)
    {
        return view('projects.ticket.create', ['project' => $project]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function detailTicket(Project $project, Ticket $ticket)
    {
        return view('projects.ticket.detail', ['project' => $project, 'ticket' => $ticket]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function editTicket(Project $project, Ticket $ticket)
    {
        return view('projects.ticket.edit', ['project' => $project, 'ticket' => $ticket]);
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