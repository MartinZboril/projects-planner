<?php

namespace App\Http\Controllers\Project\Task;

use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Models\Project;

class ProjectTaskKanbanController extends Controller
{
    /**
     * Display the tasks with kanban board of project.
     */
    public function __invoke(Project $project): View
    {
        return view('projects.tasks.kanban.index', ['project' => $project]);
    }
}
