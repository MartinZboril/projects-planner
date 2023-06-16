<?php

namespace App\Http\Controllers\Analysis;

use App\DataTables\TicketsDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class TicketAnalysisController extends Controller
{
    /**
     * Display an analyze for tickets.
     */
    public function __invoke(TicketsDataTable $ticketsDataTable): JsonResponse|View
    {
        return $ticketsDataTable->with([
            'view' => 'analysis',
        ])->render('tickets.index');
    }
}
