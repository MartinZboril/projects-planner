<?php

namespace App\Http\Controllers;

use App\Services\Dashboard\{TaskDashboard, TicketDashboard};
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     */
    public function index(): View
    {
        return view('dashboard.index');
    }

    /**
     * Show the application dashboard projects.
     */
    public function projects(): View
    {
        return view('dashboard.projects');
    }

    /**
     * Show the application dashboard tasks.
     */
    public function tasks(): View
    {
        return view('dashboard.tasks', ['data' => (new TaskDashboard)->getDashboard()]);
    }

    /**
     * Show the application dashboard tickets.
     */
    public function tickets(): View
    {
        return view('dashboard.tickets', ['data' => (new TicketDashboard)->getDashboard()]);
    }
}
