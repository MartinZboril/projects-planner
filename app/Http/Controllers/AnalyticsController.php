<?php

namespace App\Http\Controllers;

use App\Services\Analysis\ProjectAnalysis;
use App\Services\Analysis\TaskAnalysis;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display an analyze for projects.
     */
    public function projects(): View
    {
        return view('analysis.projects', ['projects' => (new ProjectAnalysis)->getAnalyze()]);
    }
    
    /**
     * Display an analyze for tasks.
     */
    public function tasks(): View
    {
        return view('analysis.tasks', ['tasks' => (new TaskAnalysis)->getAnalyze()]);
    }
}
