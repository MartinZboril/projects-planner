<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Services\Dashboard\ProjectDashboard;

class ProjectDashboardController extends Controller
{
    /**
     * Show the application project dashboard.
     */
    public function __invoke(): View
    {
        return view('dashboard.projects', ['data' => (new ProjectDashboard)->getDashboard()]);
    }
}
