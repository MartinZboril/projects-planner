<?php

namespace App\Http\Controllers\Report;

use Illuminate\View\View;
use App\Http\Controllers\Controller;

class MenuReportController extends Controller
{
    /**
     * Display menu for reporting.
     */
    public function __invoke(): View
    {
        return view('reports.index');
    }
}
