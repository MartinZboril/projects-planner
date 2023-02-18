<?php

namespace App\Http\Controllers\Report;

use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Services\Report\MilestoneReport;

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
