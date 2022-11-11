<?php

namespace App\Services\Report;

use App\Models\Project;
use App\Models\Timer;
use Illuminate\Support\Collection;

class ProjectReport
{
    /**
     * Get report for projects by year.
     */
    public function getReportPerYear(string $year = '2022'): Collection
    {
        $data = collect([
            'total_projects_count' => Project::whereYear('created_at', $year)->where('status', '!=', 3)->count(),
            'active_projects_count' => Project::whereYear('created_at', $year)->status(1)->count(),
            'done_projects_count' => Project::whereYear('created_at', $year)->status(2)->count(),
            'amount_avg' => Timer::whereYear('created_at', $year)->get()->avg('amount'),
            'spent_time_avg' => Timer::whereYear('created_at', $year)->get()->avg('total_time'),
            'report_months' => sprintf("'%s'", implode("','", $this->getReportMonths())),
            'total_projects_by_month' => $this->getProjectsByMonths($year),
            'active_projects_by_month' => $this->getProjectsByMonths($year),
            'done_projects_by_month' => $this->getProjectsByMonths($year),
            'quarterly_created_projects' => [
                1 => (
                    Project::whereYear('created_at', $year)->whereMonth('created_at', 1)->count() +
                    Project::whereYear('created_at', $year)->whereMonth('created_at', 2)->count() +
                    Project::whereYear('created_at', $year)->whereMonth('created_at', 3)->count()
                ),
                2 => (
                    Project::whereYear('created_at', $year)->whereMonth('created_at', 4)->count() +
                    Project::whereYear('created_at', $year)->whereMonth('created_at', 5)->count() +
                    Project::whereYear('created_at', $year)->whereMonth('created_at', 6)->count()
                ),
                3 => (
                    Project::whereYear('created_at', $year)->whereMonth('created_at', 7)->count() +
                    Project::whereYear('created_at', $year)->whereMonth('created_at', 8)->count() +
                    Project::whereYear('created_at', $year)->whereMonth('created_at', 9)->count()
                ),
                4 => (
                    Project::whereYear('created_at', $year)->whereMonth('created_at', 10)->count() +
                    Project::whereYear('created_at', $year)->whereMonth('created_at', 11)->count() +
                    Project::whereYear('created_at', $year)->whereMonth('created_at', 12)->count()
                ),
            ]
        ]);

        return $data;
    }

    /**
     * Get projects count by year
     */
    protected function getProjectsByMonths(string $year): array
    {
        $projects = [];
        $months = $this->getReportMonths();

        foreach ($months as $key => $month) {
            $projects[$month] = Project::whereYear('created_at', $year)->whereMonth('created_at', $key)->count();
        }

        return $projects;
    }

    protected function getReportMonths(): array
    {
        return [1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec'];
    }
}