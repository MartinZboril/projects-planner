<?php

namespace App\Http\Controllers\Analysis;

use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Services\Analysis\ProjectAnalysis;

class ProjectAnalysisController extends Controller
{
    /**
     * Display an analyze for projects.
     */
    public function __invoke(): View
    {
        return view('analysis.projects', ['projects' => (new ProjectAnalysis)->getAnalyze()]);
    }
}
