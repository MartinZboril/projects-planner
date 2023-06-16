<?php

namespace App\Http\Controllers\Dashboard;

use App\DataTables\TasksDataTable;
use App\Http\Controllers\Controller;
use App\Services\Dashboard\TaskDashboard;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class TaskDashboardController extends Controller
{
    /**
     * Show the application task dashboard.
     */
    public function __invoke(TasksDataTable $tasksDataTable): JsonResponse|View
    {
        return $tasksDataTable->with([
            'newed' => true,
            'view' => 'analysis',
        ])->render('dashboard.tasks', ['data' => (new TaskDashboard)->getDashboard()]);
    }
}
