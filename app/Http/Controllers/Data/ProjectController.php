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
     * Display the tasks with kanban board of project.
     */
    public function kanban(Project $project): View
    {
        return view('projects.kanban', ['project' => $project]);
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
}