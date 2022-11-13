<?php

namespace App\Http\Controllers;

use App\Services\Report\{ProjectReport, TaskReport, TicketReport, MilestoneReport};
use Illuminate\View\View;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
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
        return view('reports.projects', ['data' => (new ProjectReport)->getReportPerYear()]);
    }
    
    /**
     * Display a report for tasks.
     */
    public function tasks(): View
    {
        return view('reports.tasks', ['data' => (new TaskReport)->getReportPerYear()]);
    }
        
    /**
     * Display a report for tickets.
     */
    public function tickets(): View
    {
        return view('reports.tickets', ['data' => (new TicketReport)->getReportPerYear()]);
    }
            
    /**
     * Display a report for milestones.
     */
    public function milestones(): View
    {
        return view('reports.milestones', ['data' => (new MilestoneReport)->getReportPerYear()]);
    }
}
