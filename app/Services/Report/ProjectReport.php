<?php

namespace App\Services\Report;

use App\Models\Project;
use App\Models\Timer;
use Illuminate\Support\Collection;

class ProjectReport
{
    protected $reportYear;
    protected $reportMonths;

    public function __construct()
    {
        $this->reportYear = (new DateReport)->getReportYear();
        $this->reportMonths = (new DateReport)->getReportMonths();
    }

    /**
     * Get report for projects by year.
     */
    public function getReportPerYear(string $year = '2022'): Collection
    {
        $projectsByMonths = $this->getProjectsByMonths($year);
        $data = collect([
            'total_projects_count' => Project::whereYear('created_at', $year)->where('status', '!=', 3)->count(),
            'active_projects_count' => Project::whereYear('created_at', $year)->status(1)->count(),
            'done_projects_count' => Project::whereYear('created_at', $year)->status(2)->count(),
            'amount_avg' => Timer::whereYear('created_at', $year)->get()->avg('amount'),
            'spent_time_avg' => Timer::whereYear('created_at', $year)->get()->avg('total_time'),
            'report_months' => $this->reportMonths,
            'total_projects_by_month' => $projectsByMonths,
            'quarterly_created_projects' => $this->getProjectsByQuarters($year, $projectsByMonths),
        ]);

        return $data;
    }

    /**
     * Get projects count by year
     */
    protected function getProjectsByMonths(string $year): array
    {
        $projects = [];

        foreach ($this->reportYear as $quarter) {
            foreach ($quarter as $key => $month) {
                $projects[$month['index']] = Project::whereYear('created_at', $year)->whereMonth('created_at', $key)->count();
            }
        }

        return $projects;
    }

    /**
     * Get projects count by quarters year
     */
    protected function getProjectsByQuarters(string $year, array $projectsByMonths): array
    {
        $projectsByQuarters = [];

        foreach ($this->reportYear as $quarter => $months) {
            array_push($projectsByQuarters, [
                'title' => __('pages.content.dates.' . $quarter) . ', ' . $year,
                'values' => $this->getProjectsByQuartersMonths($projectsByMonths, $months)
            ]);
        }

        return $projectsByQuarters;
    }

    /**
     * Get projects count by quarters months
     */
    protected function getProjectsByQuartersMonths(array $projectsByMonths, array $months)
    {
        $projects = [];
        $totalCount = 0;

        foreach ($months as $key => $month) {
            $totalCount += $projectsByMonths[$month['index']];
            array_push($projects, ['title' => $month['text'], 'value' => $projectsByMonths[$month['index']]]);
        }

        array_push($projects, ['title' => __('pages.content.labels.total'), 'value' => $totalCount]);

        return $projects;
    }
}