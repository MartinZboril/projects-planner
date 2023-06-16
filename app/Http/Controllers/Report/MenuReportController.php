<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

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
