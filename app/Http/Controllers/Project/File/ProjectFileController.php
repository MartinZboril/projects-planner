<?php

namespace App\Http\Controllers\Project\File;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\View\View;

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
