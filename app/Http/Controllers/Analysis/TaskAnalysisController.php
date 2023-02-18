<?php

namespace App\Http\Controllers\Analysis;

use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Services\Analysis\TaskAnalysis;

class TaskAnalysisController extends Controller
{
    /**
     * Display an analyze for tasks.
     */
    public function __invoke(): View
    {
        return view('analysis.tasks', ['tasks' => (new TaskAnalysis)->getAnalyze()]);
    }
}
