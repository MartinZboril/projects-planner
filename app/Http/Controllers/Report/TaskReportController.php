<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Services\Report\TaskReport;
use Illuminate\View\View;

class TaskReportController extends Controller
{
    protected $year;

    public function __construct()
    {
        $this->year = now()->format('Y');
    }

    /**
     * Display a report for tasks.
     */
    public function __invoke(): View
    {
        return view('reports.tasks', ['data' => (new TaskReport)->getReportPerYear($this->year)]);
    }
}
