<?php

namespace App\Http\Controllers;

use App\Services\Report\ProjectReport;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a report for projects.
     */
    public function projects(): View
    {
        return view('reports.projects', ['data' => (new ProjectReport)->getReportPerYear()]);
    }
}
