<?php

namespace App\Http\Controllers\Analysis;

use App\DataTables\MilestonesDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class MilestoneAnalysisController extends Controller
{
    /**
     * Display an analyze for milestones.
     */
    public function __invoke(MilestonesDataTable $milestonesDataTable): JsonResponse|View
    {
        return $milestonesDataTable->with([
            'view' => 'analysis',
        ])->render('analysis.milestones');
    }
}
