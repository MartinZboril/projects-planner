<?php

namespace App\Services\Report;

use App\Models\Task;
use Illuminate\Support\Collection;

class TaskReport
{
    /**
     * Get report for tasks by year.
     */
    public function getReportPerYear(string $year = '2022'): Collection
    {
        $data = collect([
            'total_tasks_count' => Task::whereYear('created_at', $year)->count(),
            'active_tasks_count' => Task::whereYear('created_at', $year)->active()->count(),
            'done_tasks_count' => Task::whereYear('created_at', $year)->done()->count(),
            'overdue_tasks_count' => Task::whereYear('created_at', $year)->active()->overdue()->count(),
            'report_months' => sprintf("'%s'", implode("','", $this->getReportMonths())),
            'total_tasks_by_month' => $this->getTasksByMonths($year),
            'quarterly_created_tasks' => [
                1 => (
                    Task::whereYear('created_at', $year)->whereMonth('created_at', 1)->count() +
                    Task::whereYear('created_at', $year)->whereMonth('created_at', 2)->count() +
                    Task::whereYear('created_at', $year)->whereMonth('created_at', 3)->count()
                ),
                2 => (
                    Task::whereYear('created_at', $year)->whereMonth('created_at', 4)->count() +
                    Task::whereYear('created_at', $year)->whereMonth('created_at', 5)->count() +
                    Task::whereYear('created_at', $year)->whereMonth('created_at', 6)->count()
                ),
                3 => (
                    Task::whereYear('created_at', $year)->whereMonth('created_at', 7)->count() +
                    Task::whereYear('created_at', $year)->whereMonth('created_at', 8)->count() +
                    Task::whereYear('created_at', $year)->whereMonth('created_at', 9)->count()
                ),
                4 => (
                    Task::whereYear('created_at', $year)->whereMonth('created_at', 10)->count() +
                    Task::whereYear('created_at', $year)->whereMonth('created_at', 11)->count() +
                    Task::whereYear('created_at', $year)->whereMonth('created_at', 12)->count()
                ),
            ]
        ]);

        return $data;
    }

    /**
     * Get taskss count by year
     */
    protected function getTasksByMonths(string $year): array
    {
        $tasks = [];
        $months = $this->getReportMonths();

        foreach ($months as $key => $month) {
            $tasks[$month] = Task::whereYear('created_at', $year)->whereMonth('created_at', $key)->count();
        }

        return $tasks;
    }

    protected function getReportMonths(): array
    {
        return [1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec'];
    }
}