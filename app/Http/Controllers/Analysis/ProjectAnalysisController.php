<?php

namespace App\Http\Controllers\Analysis;

use App\DataTables\ProjectsDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class ProjectAnalysisController extends Controller
{
    /**
     * Display an analyze for projects.
     */
    public function __invoke(ProjectsDataTable $projectsDataTable): JsonResponse|View
    {
        return $projectsDataTable->with([
            'view' => 'analysis',
        ])->render('analysis.projects');
    }
}
