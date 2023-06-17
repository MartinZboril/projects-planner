<?php

namespace App\Http\Controllers\Analysis;

use App\DataTables\TimersDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

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
