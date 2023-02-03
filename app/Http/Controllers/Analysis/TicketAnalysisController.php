<?php

namespace App\Http\Controllers\Analysis;

use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Services\Analysis\TicketAnalysis;

class TicketAnalysisController extends Controller
{
    /**
     * Display an analyze for tickets.
     */
    public function __invoke(): View
    {
        return view('analysis.tickets', ['tickets' => (new TicketAnalysis)->getAnalyze()]);
    }
}
