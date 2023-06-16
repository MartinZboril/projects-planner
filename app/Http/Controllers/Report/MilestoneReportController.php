<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Services\Report\MilestoneReport;
use Illuminate\View\View;

class MilestoneReportController extends Controller
{
    protected $year;

    public function __construct()
    {
        $this->year = now()->format('Y');
    }

    /**
     * Display a report for milestones.
     */
    public function __invoke(): View
    {
        return view('reports.milestones', ['data' => (new MilestoneReport)->getReportPerYear($this->year)]);
    }
}
