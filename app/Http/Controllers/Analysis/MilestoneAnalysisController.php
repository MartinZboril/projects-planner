<?php

namespace App\Http\Controllers\Analysis;

use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Services\Analysis\MilestoneAnalysis;

class MilestoneAnalysisController extends Controller
{
    /**
     * Display an analyze for milestones.
     */
    public function __invoke(): View
    {
        return view('analysis.milestones', ['milestones' => (new MilestoneAnalysis)->getAnalyze()]);
    }
}
