<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Client;
use App\Models\User;
use App\Models\ProjectUser;
use App\Models\Task;
use App\Models\Milestone;
use App\Models\ToDo;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function storeTask(Request $request, Project $project)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'start_date' => ['required', 'date'],
            'due_date' => ['required', 'date'],
            'description' => ['required'],
        ]);

        if ($validator->fails()) {
            return redirect()
                    ->route('projects.task.create', ['project' => $project])
                    ->withErrors($validator)
                    ->withInput();
        }

        $task = new Task();

        $task->name = $request->name;
        $task->project_id = $project->id;
        $task->milestone_id = $request->milestone_id ? $request->milestone_id : null;
        $task->status_id = 1;
        $task->author_id = Auth::id();
        $task->user_id = $request->user_id;
        $task->start_date = $request->start_date;
        $task->due_date = $request->due_date;
        $task->description = $request->description;

        $task->save();

        Session::flash('message', 'Task was created!');
        Session::flash('type', 'info');

        return ($request->create_and_close) ? redirect()->route('projects.tasks', ['project' => $project]) : redirect()->route('projects.task.detail', ['project' => $project, 'task' => $task]);
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function updateTask(Request $request, Project $project, Task $task)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'start_date' => ['required', 'date'],
            'due_date' => ['required', 'date'],
            'description' => ['required'],
        ]);

        if ($validator->fails()) {
            return redirect()
                    ->route('projects.task.edit', ['project' => $project, 'task' => $task])
                    ->withErrors($validator)
                    ->withInput();
        }

        Task::where('id', $task->id)
                    ->update([
                        'name' => $request->name,
                        'project_id' => $project->id,
                        'milestone_id' => $request->milestone_id ? $request->milestone_id : null,
                        'user_id' => $request->user_id,
                        'start_date' => $request->start_date,
                        'due_date' => $request->due_date,
                        'description' => $request->description,
                    ]);

        Session::flash('message', 'Task was updated!');
        Session::flash('type', 'info');

        return ($request->save_and_close) ? redirect()->route('projects.tasks', ['project' => $project]) : redirect()->route('projects.task.detail', ['project' => $project, 'task' => $task]);
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

        return ($request->type == 'kanban') ? redirect()->route('projects.kanban', ['project' => $task->project->id]) : redirect()->route('projects.task.detail', ['project' => $project, 'task' => $task]);
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

        return ($request->type == 'kanban') ? redirect()->route('projects.kanban', ['project' => $task->project->id]) : redirect()->route('projects.task.detail', ['project' => $project, 'task' => $task]);
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

        return ($request->type == 'kanban') ? redirect()->route('projects.kanban', ['project' => $task->project->id]) : redirect()->route('projects.task.detail', ['project' => $project, 'task' => $task]);
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

        return ($request->type == 'kanban') ? redirect()->route('projects.kanban', ['project' => $task->project->id]) : redirect()->route('projects.task.detail', ['project' => $project, 'task' => $task]);
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

        return ($request->type == 'kanban') ? redirect()->route('projects.kanban', ['project' => $task->project->id]) : redirect()->route('projects.task.detail', ['project' => $project, 'task' => $task]);
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function storeTodo(Request $request, Project $project, Task $task)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'deadline' => ['required', 'date'],
            'description' => ['max:65553'],
        ]);

        if ($validator->fails()) {
            return redirect()
                    ->route('projects.todo.create', ['project' => $project, 'task' => $task])
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

        return redirect()->route('projects.task.detail', ['project' => $project, 'task' => $task]);
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @param  \App\Models\Task  $task
     * @param  \App\Models\ToDo  $todo
     * @return \Illuminate\Http\Response
     */
    public function updateToDo(Request $request, Project $project, Task $task, ToDo $todo)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'deadline' => ['required', 'date'],
            'is_finished' => ['boolean'],
            'description' => ['max:65553'],
        ]);

        if ($validator->fails()) {
            return redirect()
                    ->route('projects.todo.edit', ['project' => $project, 'task' => $task, 'todo' => $todo])
                    ->withErrors($validator)
                    ->withInput();
        }

        ToDo::where('id', $todo->id)
                    ->update([
                        'name' => $request->name,
                        'deadline' => $request->deadline,
                        'is_finished' => $request->is_finished,
                        'description' => $request->description,
                    ]);

        Session::flash('message', 'ToDo was updated!');
        Session::flash('type', 'info');

        return redirect()->route('projects.task.detail', ['project' => $project, 'task' => $task]);
    }

    /**
     * Check the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @param  \App\Models\Task  $task
     * @param  \App\Models\ToDo  $todo
     * @return \Illuminate\Http\Response
     */
    public function checkToDo(Request $request, Project $project, Task $task, ToDo $todo)
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

        return redirect()->route('projects.task.detail', ['project' => $project, 'task' => $task]);
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function storeTicket(Request $request, Project $project, Ticket $ticket)
    {
        $validator = Validator::make($request->all(), [
            'subject' => ['required', 'string', 'max:255'],
            'assignee_id' => ['sometimes', 'required', 'integer', 'exists:users,id'],
            'type' => ['required', 'integer', 'in:1,2,3,4'],
            'priority' => ['required', 'integer', 'in:1,2,3,4'],
            'due_date' => ['required', 'date'],
            'message' => ['required', 'max:65553'],
        ]);

        if ($validator->fails()) {
            return redirect()
                    ->route('projects.ticket.create', ['project' => $project])
                    ->withErrors($validator)
                    ->withInput();
        }

        $ticket = new Ticket();

        $ticket->subject = $request->subject;
        $ticket->project_id = $project->id;
        $ticket->reporter_id = Auth::id();
        $ticket->assignee_id = $request->assignee_id;
        $ticket->type = $request->type;
        $ticket->priority = $request->priority;
        $ticket->due_date = $request->due_date;
        $ticket->message = $request->message;

        $ticket->save();

        Session::flash('message', 'Ticket was created!');
        Session::flash('type', 'info');

        return ($request->create_and_close) ? redirect()->route('projects.tickets', ['project' => $project]) : redirect()->route('projects.ticket.detail', ['project' => $project, 'ticket' => $ticket]);
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function updateTicket(Request $request, Project $project, Ticket $ticket)
    {
        $validator = Validator::make($request->all(), [
            'subject' => ['required', 'string', 'max:255'],
            'assignee_id' => ['required', 'integer', 'exists:users,id'],
            'type' => ['required', 'integer', 'in:1,2,3,4'],
            'priority' => ['required', 'integer', 'in:1,2,3,4'],
            'status' => ['required', 'integer', 'in:1,2,3'],
            'due_date' => ['required', 'date'],
            'message' => ['required', 'max:65553'],
        ]);

        if ($validator->fails()) {
            return redirect()
                    ->route('projects.ticket.edit', ['project' => $project, 'ticket' => $ticket])
                    ->withErrors($validator)
                    ->withInput();
        }

        Ticket::where('id', $ticket->id)
                    ->update([
                        'subject' => $request->subject,
                        'project_id' => $project->id,
                        'assignee_id' => $request->assignee_id,
                        'status' => $request->status,
                        'type' => $request->type,
                        'priority' => $request->priority,
                        'due_date' => $request->due_date,
                        'message' => $request->message,
                    ]);

        if(!ProjectUser::where('project_id', $project->id)->where('user_id', $request->assignee_id)->first()) {
            $projectUser = new ProjectUser;

            $projectUser->project_id = $project->id;
            $projectUser->user_id = $request->assignee_id;

            $projectUser->save();
        }

        Session::flash('message', 'Ticket was updated!');
        Session::flash('type', 'info');

        return ($request->save_and_close) ? redirect()->route('projects.tickets', ['project' => $project]) : redirect()->route('projects.ticket.detail', ['project' => $project, 'ticket' => $ticket]);
    }

    /**
     * Open the ticket.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function openTicket(Request $request, Project $project, Ticket $ticket)
    {
        Ticket::where('id', $ticket->id)
                    ->update([
                        'status' => 1,
                    ]);

        Session::flash('message', 'Ticket has been opened!');
        Session::flash('type', 'info');

        return redirect()->route('projects.ticket.detail', ['project' => $project, 'ticket' => $ticket]);
    }

    /**
     * Close the ticket.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function closeTicket(Request $request, Project $project, Ticket $ticket)
    {
        Ticket::where('id', $ticket->id)
                    ->update([
                        'status' => 2,
                    ]);

        Session::flash('message', 'Ticket has been closed!');
        Session::flash('type', 'info');

        return redirect()->route('projects.ticket.detail', ['project' => $project, 'ticket' => $ticket]);
    }

    /**
     * Archive the ticket.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function archiveTicket(Request $request, Project $project, Ticket $ticket)
    {
        Ticket::where('id', $ticket->id)
                    ->update([
                        'status' => 3,
                    ]);

        Session::flash('message', 'Ticket has been archived!');
        Session::flash('type', 'info');

        return redirect()->route('projects.ticket.detail', ['project' => $project, 'ticket' => $ticket]);
    }

    /**
     * Convert the ticket.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function convertTicket(Request $request, Project $project, Ticket $ticket)
    {
        $task = new Task();

        $task->name = $ticket->subject;
        $task->project_id = $ticket->project_id;
        $task->status_id = 1;
        $task->author_id = Auth::id();
        $task->user_id = $ticket->assignee_id;
        $task->start_date = $ticket->created_at;
        $task->due_date = $ticket->due_date;
        $task->description = $ticket->message;

        $task->save();

        if(!ProjectUser::where('project_id', $ticket->project_id)->where('user_id', $ticket->assignee_id)->first()) {
            $projectUser = new ProjectUser;

            $projectUser->project_id = $ticket->project_id;
            $projectUser->user_id = $ticket->assignee_id;

            $projectUser->save();
        }

        Ticket::where('id', $ticket->id)
                    ->update([
                        'status' => 3,
                        'is_convert' => true
                    ]);        

        Session::flash('message', 'Task was created!');
        Session::flash('type', 'info');

        return redirect()->route('projects.task.detail', ['project' => $project, 'task' => $task]);
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
