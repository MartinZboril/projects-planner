<?php

namespace App\Services\Report;

use App\Models\Project;
use App\Models\Timer;
use Illuminate\Support\Collection;

class ProjectReport
{
    protected $builderReport;
    
    public function __construct()
    {
        $this->builderReport = new BuilderReport;
    }

    /**
     * Get report for projects by year.
     */
    public function getReportPerYear(string $year): Collection
    {
        $projectsByMonths = $this->getProjectsByMonths($year);
        $data = collect([
            'year' => $year,
            'total_projects_count' => Project::whereYear('created_at', '<=', $year)->count(),
            'active_projects_count' => Project::whereYear('created_at', '<=', $year)->active()->count(),
            'done_projects_count' => Project::whereYear('created_at', '<=', $year)->done()->count(),
            'overdue_projects_count' => Project::whereYear('created_at', '<=', $year)->active()->overdue()->count(),
            'amount_avg' => Timer::whereYear('created_at', '<=', $year)->get()->avg('amount'),
            'spent_time_avg' => Timer::whereYear('created_at', '<=', $year)->get()->avg('total_time'),
            'report_months' => $this->builderReport->reportMonthsIndexes,
            'total_projects_by_month' => $projectsByMonths,
            'quarterly_created_projects' => $this->builderReport->getItemsByQuarters($year, $projectsByMonths),
        ]);

        return $data;
    }
    
    /**
     * Get projects count by year
     */
    protected function getProjectsByMonths(string $year): Collection
    {
        $projects = collect();

        $this->builderReport->reportMonthsFull->each(function ($month, $key) use($projects, $year) {
            $projects->put($month['index'], Project::whereYear('created_at', $year)->whereMonth('created_at', $key + 1)->count());
        });

        return $projects;
    }
}