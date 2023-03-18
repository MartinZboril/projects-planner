<?php

namespace App\Http\Controllers\Analysis;

use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use App\DataTables\ProjectsDataTable;
use App\Http\Controllers\Controller;

class ProjectAnalysisController extends Controller
{
    /**
     * Display an analyze for projects.
     */
    public function __invoke(ProjectsDataTable $projectsDataTable): JsonResponse|View
    {
        return $projectsDataTable->render('analysis.projects');
    }
}
