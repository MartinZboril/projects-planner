<?php

namespace App\Services\Dashboard;

use App\Enums\TaskStatusEnum;
use App\Interfaces\DashboardInterface;
use App\Models\Task;
use App\Models\ToDo;
use App\Services\Report\TaskReport;
use Illuminate\Support\Collection;

class TaskDashboard implements DashboardInterface
{
    /**
     * Get dashboard for tasks.
     */
    public function getDashboard(): Collection
    {
        $year = now()->format('Y');
        $data = collect([
            'today_tasks_count' => Task::whereDate('created_at', now()->format('Y-m-d'))->count(),
            'this_week_tasks_count' => Task::whereBetween('created_at', [
                now()->startOfWeek()->format('Y-m-d'),
                now()->endOfWeek()->format('Y-m-d'),
            ])->count(),
            'month_tasks_count' => Task::whereYear('created_at', '<=', $year)->whereMonth('created_at', now()->month)->count(),
            'last_month_tasks_count' => Task::whereYear('created_at', '<=', $year)->whereMonth('created_at', now()->subMonth()->month)->count(),
            'total_tasks_count' => Task::whereYear('created_at', '<=', $year)->count(),
            'active_tasks_count' => Task::active()->stopped(false)->count(),
            'pause_tasks_count' => Task::stopped(true)->count(),
            'overdue_tasks_count' => Task::active()->overdue()->count(),
            'overdue_tasks' => Task::active()->overdue()->get(),
            'new_tasks_count' => Task::status(TaskStatusEnum::new)->count(),
            'new_tasks' => Task::status(TaskStatusEnum::new)->get(),
            'in_progress_tasks_count' => Task::status(TaskStatusEnum::in_progress)->count(),
            'complete_tasks_count' => Task::status(TaskStatusEnum::complete)->count(),
            'report' => (new TaskReport)->getReportPerYear($year),
            'overdue_todos' => ToDo::finished(false)->overdue()->get(),
        ]);

        return $data;
    }
}
