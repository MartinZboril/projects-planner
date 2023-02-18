<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Services\Dashboard\SummaryDashboard;

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
