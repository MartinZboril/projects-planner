<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Services\Report\ProjectReport;
use Illuminate\View\View;

class ProjectReportController extends Controller
{
    protected $year;

    public function __construct()
    {
        $this->year = now()->format('Y');
    }

    /**
     * Display a report for projects.
     */
    public function __invoke(): View
    {
        return view('reports.projects', ['data' => (new ProjectReport)->getReportPerYear($this->year)]);
    }
}
