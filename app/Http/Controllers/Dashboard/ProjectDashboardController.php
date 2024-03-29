<?php

namespace App\Http\Controllers\Dashboard;

use App\DataTables\MilestonesDataTable;
use App\Http\Controllers\Controller;
use App\Services\Dashboard\ProjectDashboard;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class ProjectDashboardController extends Controller
{
    /**
     * Show the application project dashboard.
     */
    public function __invoke(MilestonesDataTable $milestonesDataTable): JsonResponse|View
    {
        return $milestonesDataTable->with([
            'overdue' => true,
            'view' => 'analysis',
        ])->render('dashboard.projects', ['data' => (new ProjectDashboard)->getDashboard()]);
    }
}
