<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Services\Dashboard\TaskDashboard;

class TaskDashboardController extends Controller
{
    /**
     * Show the application task dashboard.
     */
    public function __invoke(): View
    {
        return view('dashboard.tasks', ['data' => (new TaskDashboard)->getDashboard()]);
    }
}
