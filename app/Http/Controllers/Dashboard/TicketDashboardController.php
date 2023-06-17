<?php

namespace App\Http\Controllers\Dashboard;

use App\DataTables\TicketsDataTable;
use App\Http\Controllers\Controller;
use App\Services\Dashboard\TicketDashboard;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class TicketDashboardController extends Controller
{
    /**
     * Show the application ticket dashboard.
     */
    public function __invoke(TicketsDataTable $ticketsDataTable): JsonResponse|View
    {
        return $ticketsDataTable->with([
            'unassigned' => true,
            'view' => 'analysis',
        ])->render('dashboard.tickets', ['data' => (new TicketDashboard)->getDashboard()]);
    }
}
