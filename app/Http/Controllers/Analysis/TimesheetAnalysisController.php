<?php

namespace App\Http\Controllers\Analysis;

use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use App\DataTables\TimersDataTable;
use App\Http\Controllers\Controller;

class TimesheetAnalysisController extends Controller
{
    /**
     * Display an analyze for timesheets.
     */
    public function __invoke(TimersDataTable $timersDataTable): JsonResponse|View
    {
        return $timersDataTable->with([
            'view' => 'analysis',
        ])->render('analysis.timesheets');
    }
}
