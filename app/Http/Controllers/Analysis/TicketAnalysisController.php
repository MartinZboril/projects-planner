<?php

namespace App\Http\Controllers\Analysis;

use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\DataTables\TicketsDataTable;

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
