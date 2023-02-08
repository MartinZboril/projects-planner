<?php

namespace App\Http\Controllers\Project;

use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Models\Project;

class ProjectFileController extends Controller
{
    /**
     * Display the files of project.
     */
    public function index(Project $project): View
    {
        return view('projects.files.index', ['project' => $project]);
    }
}
