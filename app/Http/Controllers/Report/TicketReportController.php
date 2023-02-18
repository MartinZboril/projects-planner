<?php

namespace App\Http\Controllers\Report;

use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Services\Report\TicketReport;

class TicketReportController extends Controller
{
    protected $year;

    public function __construct()
    {
        $this->year = now()->format('Y');
    }

    /**
     * Display a report for tickets.
     */
    public function __invoke(): View
    {
        return view('reports.tickets', ['data' => (new TicketReport)->getReportPerYear($this->year)]);
    }
}
