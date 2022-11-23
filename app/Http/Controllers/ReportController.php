<?php

namespace App\Http\Controllers;

use App\Services\Report\{ProjectReport, TaskReport, TicketReport, MilestoneReport, TimesheetReport};
use Illuminate\View\View;

class ReportController extends Controller
{
    protected $year;

    public function __construct()
    {
        $this->middleware('auth');
        $this->year = now()->format('Y');
    }

    public function index(): View
    {
        return view('reports.index');
    }

    /**
     * Display a report for projects.
     */
    public function projects(): View
    {
        return view('reports.projects', ['data' => (new ProjectReport)->getReportPerYear($this->year)]);
    }
    
    /**
     * Display a report for tasks.
     */
    public function tasks(): View
    {
        return view('reports.tasks', ['data' => (new TaskReport)->getReportPerYear($this->year)]);
    }
        
    /**
     * Display a report for tickets.
     */
    public function tickets(): View
    {
        return view('reports.tickets', ['data' => (new TicketReport)->getReportPerYear($this->year)]);
    }
            
    /**
     * Display a report for milestones.
     */
    public function milestones(): View
    {
        return view('reports.milestones', ['data' => (new MilestoneReport)->getReportPerYear($this->year)]);
    }
                
    /**
     * Display a report for timesheets.
     */
    public function timesheets(): View
    {
        return view('reports.timesheets', ['data' => (new TimesheetReport)->getReportPerYear($this->year)]);
    }
}
