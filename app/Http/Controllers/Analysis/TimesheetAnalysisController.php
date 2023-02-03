<?php

namespace App\Http\Controllers\Analysis;

use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Services\Analysis\TimesheetAnalysis;

class TimesheetAnalysisController extends Controller
{
    /**
     * Display an analyze for timesheets.
     */
    public function __invoke(): View
    {
        return view('analysis.timesheets', ['timers' => (new TimesheetAnalysis)->getAnalyze()]);
    }
}
