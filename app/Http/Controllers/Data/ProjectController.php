<?php

namespace App\Http\Controllers\Data;

use Exception;
use Illuminate\View\View;
use App\Services\FlashService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\Data\ProjectService;
use Illuminate\Http\RedirectResponse;
use App\Models\{Client, Comment, Milestone, Note, Project, Task, Ticket, ToDo, User};
use App\Http\Requests\Project\{ChangeProjectRequest, MarkProjectRequest, StoreProjectRequest, UpdateProjectRequest};

class ProjectController extends Controller
{
    protected $projectService;
    protected $flashService;

    public function __construct(ProjectService $projectService, FlashService $flashService)
    {
        $this->middleware('auth');
        $this->projectService = $projectService;
        $this->flashService = $flashService;
    }

    /**
     * Display a listing of the projects.
     */
    public function index(): View
    {
        return view('projects.index', ['projects' => Project::all()]);
    }

    /**
     * Display the tasks of project.
     */
    public function tasks(Project $project): View
    {
        return view('projects.tasks', ['project' => $project]);
    }

    /**
     * Display the tasks with kanban board of project.
     */
    public function kanban(Project $project): View
    {
        return view('projects.kanban', ['project' => $project]);
    }
    
    /**
     * Display the milestones of project.
     */
    public function milestones(Project $project): View
    {
        return view('projects.milestones', ['project' => $project]);
    }

    /**
     * Display the timesheets of project.
     */
    public function timesheets(Project $project): View
    {
        return view('projects.timesheets', ['project' => $project]);
    }

    /**
     * Display the tickets of project.
     */
    public function tickets(Project $project): View
    {
        return view('projects.tickets', ['project' => $project]);
    }

    /**
     * Display the notes of project.
     */
    public function notes(Project $project): View
    {
        return view('projects.notes', ['project' => $project]);
    }

    /**
     * Display the files of project.
     */
    public function files(Project $project): View
    {
        return view('projects.files', ['project' => $project]);
    }
    
    /**
     * Display the comments of project.
     */
    public function comments(Project $project): View
    {
        return view('projects.comments', ['project' => $project, 'comment' => new Comment]);
    }

    /**
     * Show the form for creating a new project.
     */
    public function create(): View
    {
        return view('projects.create', ['project' => new Project, 'clients' => Client::all(), 'users' => User::all()]);
    }

    /**
     * Store a newly created project in storage.
     */
    public function store(StoreProjectRequest $request): RedirectResponse
    {
        try {
            $project = $this->projectService->save(new Project, $request->safe());
            $this->flashService->flash(__('messages.project.create'), 'info');
            return $this->projectService->setUpRedirect($request->has('save_and_close'), $project);
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
    }

    /**
     * Display the project.
     */
    public function detail(Project $project): View
    {
        return view('projects.detail', ['project' => $project]);
    }

    /**
     * Show the form for editing the project.
     */
    public function edit(Project $project): View
    {
        return view('projects.edit', ['project' => $project, 'clients' => Client::all(), 'users' => User::all()]);
    }

    /**
     * Update the project in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project): RedirectResponse
    {
        try {
            $project = $this->projectService->save($project, $request->safe());
            $this->flashService->flash(__('messages.project.update'), 'info');
            return $this->projectService->setUpRedirect($request->has('save_and_close'), $project);
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        } 
    }

    /**
     * Show the form for creating a new task of project.
     */
    public function createTask(Project $project): View
    {
        return view('projects.task.create', ['project' => $project, 'milestones' => Milestone::where('project_id', $project->id)->get(), 'users' => User::all(), 'task' => new Task]);
    }

    /**
     * Display the task of project.
     */
    public function detailTask(Project $project, Task $task): View
    {
        return view('projects.task.detail', ['project' => $project, 'task' => $task, 'milestones' => Milestone::where('project_id', $project->id)->get(), 'users' => User::all(), 'comment' => new Comment]);
    }

    /**
     * Show the form for editing the task of project.
     */
    public function editTask(Project $project, Task $task): View
    {
        return view('projects.task.edit', ['project' => $project, 'task' => $task, 'milestones' => Milestone::where('project_id', $project->id)->get(), 'users' => User::all()]);
    }

    /**
     * Show the form for creating a new todo of project.
     */
    public function createToDo(Project $project, Task $task): View
    {
        return view('projects.todo.create', ['project' => $project, 'task' => $task, 'todo' => new ToDo]);
    }

    /**
     * Show the form for editing the todo of project.
     */
    public function editToDo(Project $project, Task $task, ToDo $todo): View
    {
        return view('projects.todo.edit', ['project' => $project, 'task' => $task, 'todo' => $todo]);
    }

    /**
     * Show the form for creating a new ticket of project.
     */
    public function createTicket(Project $project): View
    {
        return view('projects.ticket.create', ['project' => $project, 'ticket' => new Ticket]);
    }

    /**
     * Display the ticket of project.
     */
    public function detailTicket(Project $project, Ticket $ticket): View
    {
        return view('projects.ticket.detail', ['project' => $project, 'ticket' => $ticket, 'comment' => new Comment]);
    }

    /**
     * Show the form for editing the ticket of project.
     */
    public function editTicket(Project $project, Ticket $ticket): View
    {
        return view('projects.ticket.edit', ['project' => $project, 'ticket' => $ticket]);
    }

    /**
     * Change working status of the project.
     */
    public function change(ChangeProjectRequest $request, Project $project): RedirectResponse
    {
        try {
            $this->projectService->change($project, $request->status);
            $this->flashService->flash(__('messages.project.' . $project->status->name), 'info');
            return redirect()->back();
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
    }

    /**
     * Show the form for creating a new note of project.
     */
    public function createNote(Project $project): View
    {
        return view('projects.note.create', ['project' => $project, 'note' => new Note]);
    }

    /**
     * Show the form for editing the note of project.
     */
    public function editNote(Project $project, Note $note): View
    {
        return view('projects.note.edit', ['project' => $project, 'note' => $note]);
    }
    
    /**
     * Mark selected project.
     */
    public function mark(Project $project): RedirectResponse
    {
        try {
            $project = $this->projectService->mark($project);
            $this->flashService->flash(__('messages.project.' . ($project->is_marked ? 'mark' : 'unmark')), 'info');
            return redirect()->back();
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
    }    
}