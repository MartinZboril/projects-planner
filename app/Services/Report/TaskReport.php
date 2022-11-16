<?php

namespace App\Services\Report;

use App\Models\Task;
use Illuminate\Support\Collection;

class TaskReport
{
    protected $reportYear;
    protected $reportMonths;

    public function __construct()
    {
        $this->reportYear = (new DateReport)->getReportYear();
        $this->reportMonths = (new DateReport)->getReportMonths();
    }

    /**
     * Get report for tasks by year.
     */
    public function getReportPerYear(string $year = '2022'): Collection
    {
        $tasksByMonths = $this->getTasksByMonths($year);
        $data = collect([
            'total_tasks_count' => Task::whereYear('created_at', $year)->count(),
            'active_tasks_count' => Task::whereYear('created_at', $year)->active()->count(),
            'done_tasks_count' => Task::whereYear('created_at', $year)->done()->count(),
            'overdue_tasks_count' => Task::whereYear('created_at', $year)->active()->overdue()->count(),
            'report_months' => $this->reportMonths,
            'total_tasks_by_month' => $tasksByMonths,
            'quarterly_created_tasks' => $this->getTasksByQuarters($year, $tasksByMonths),
        ]);

        return $data;
    }

    /**
     * Get tasks count by year
     */
    protected function getTasksByMonths(string $year): array
    {
        $tasks = [];

        foreach ($this->reportYear as $quarter) {
            foreach ($quarter as $key => $month) {
                $tasks[$month['index']] = Task::whereYear('created_at', $year)->whereMonth('created_at', $key)->count();
            }
        }

        return $tasks;
    }

    /**
     * Get tasks count by quarters year
     */
    protected function getTasksByQuarters(string $year, array $tasksByMonths): array
    {
        $tasksByQuarters = [];

        foreach ($this->reportYear as $quarter => $months) {
            array_push($tasksByQuarters, [
                'title' => __('pages.content.dates.' . $quarter) . ', ' . $year,
                'values' => $this->getTasksByQuartersMonths($tasksByMonths, $months)
            ]);
        }

        return $tasksByQuarters;
    }

    /**
     * Get tasks count by quarters months
     */
    protected function getTasksByQuartersMonths(array $tasksByMonths, array $months)
    {
        $tasks = [];
        $totalCount = 0;

        foreach ($months as $key => $month) {
            $totalCount += $tasksByMonths[$month['index']];
            array_push($tasks, ['title' => $month['text'], 'value' => $tasksByMonths[$month['index']]]);
        }

        array_push($tasks, ['title' => __('pages.content.labels.total'), 'value' => $totalCount]);

        return $tasks;
    }
}