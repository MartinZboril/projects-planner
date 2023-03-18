<?php

namespace App\Http\Controllers\Analysis;

use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use App\DataTables\MilestonesDataTable;
use App\Http\Controllers\Controller;
use App\Services\Analysis\MilestoneAnalysis;

class MilestoneAnalysisController extends Controller
{
    /**
     * Display an analyze for milestones.
     */
    public function __invoke(MilestonesDataTable $milestonesDataTable): JsonResponse|View
    {
        return $milestonesDataTable->with([
            'view' => 'analysis',
        ])->render('analysis.milestones', ['milestones' => (new MilestoneAnalysis)->getAnalyze()]);
    }
}
