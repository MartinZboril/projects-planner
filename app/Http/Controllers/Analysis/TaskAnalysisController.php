<?php

namespace App\Http\Controllers\Analysis;

use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use App\DataTables\TasksDataTable;
use App\Http\Controllers\Controller;

class TaskAnalysisController extends Controller
{
    /**
     * Display an analyze for tasks.
     */
    public function __invoke(TasksDataTable $tasksDataTable): JsonResponse|View
    {
        return $tasksDataTable->with([
            'view' => 'analysis',
        ])->render('tasks.index');
    }
}
