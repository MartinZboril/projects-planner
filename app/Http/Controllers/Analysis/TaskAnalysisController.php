<?php

namespace App\Http\Controllers\Analysis;

use App\DataTables\TasksDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

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
