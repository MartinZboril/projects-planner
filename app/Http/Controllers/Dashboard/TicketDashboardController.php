<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Services\Dashboard\TicketDashboard;

class TicketDashboardController extends Controller
{
    /**
     * Show the application ticket dashboard.
     */
    public function __invoke(): View
    {
        return view('dashboard.tickets', ['data' => (new TicketDashboard)->getDashboard()]);
    }
}
