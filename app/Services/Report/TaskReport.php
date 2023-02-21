<?php

namespace App\Services\Report;

use App\Models\Task;
use Illuminate\Support\Collection;

class TaskReport
{
    public function __construct(public BuilderReport $builderReport=new BuilderReport)
    {
    }

    /**
     * Get report for tasks by year.
     */
    public function getReportPerYear(string $year): Collection
    {
        $tasksByMonths = $this->getTasksByMonths($year);
        $data = collect([
            'year' => $year,
            'total_tasks_count' => Task::whereYear('created_at', '<=', $year)->count(),
            'active_tasks_count' => Task::whereYear('created_at', '<=', $year)->active()->count(),
            'done_tasks_count' => Task::whereYear('created_at', '<=', $year)->done()->count(),
            'overdue_tasks_count' => Task::whereYear('created_at', '<=', $year)->active()->overdue()->count(),
            'report_months' => $this->builderReport->reportMonthsIndexes,
            'total_tasks_by_month' => $tasksByMonths,
            'quarterly_created_tasks' => (new BuilderReport)->getItemsByQuarters($year, $tasksByMonths),
        ]);

        return $data;
    }

    /**
     * Get tasks count by year
     */
    protected function getTasksByMonths(string $year): Collection
    {
        $tasks = collect();

        $this->builderReport->reportMonthsFull->each(function ($month, $key) use($tasks, $year) {
            $tasks->put($month['index'], Task::whereYear('created_at', $year)->whereMonth('created_at', $key + 1)->count());
        });

        return $tasks;
    }
}