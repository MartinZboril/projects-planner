<?php

namespace App\Http\Controllers\Analysis;

use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use App\DataTables\MilestonesDataTable;
use App\Http\Controllers\Controller;

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
