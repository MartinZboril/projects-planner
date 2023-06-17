<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Services\Report\TimesheetReport;
use Illuminate\View\View;

class TimesheetReportController extends Controller
{
    protected $year;

    public function __construct()
    {
        $this->year = now()->format('Y');
    }

    /**
     * Display a report for tickets.
     */
    public function __invoke(): View
    {
        return view('reports.timesheets', ['data' => (new TimesheetReport)->getReportPerYear($this->year)]);
    }
}
