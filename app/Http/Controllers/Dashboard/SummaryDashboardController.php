<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\SummaryDashboard;
use Illuminate\View\View;

class SummaryDashboardController extends Controller
{
    /**
     * Show the application summary dashboard.
     */
    public function __invoke(): View
    {
        return view('dashboard.index', ['data' => (new SummaryDashboard)->getDashboard()]);
    }
}
