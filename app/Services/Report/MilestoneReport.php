<?php

namespace App\Services\Report;

use App\Models\Milestone;
use Illuminate\Support\Collection;

class MilestoneReport
{
    protected $reportYear;
    protected $reportMonths;

    public function __construct()
    {
        $this->reportYear = (new DateReport)->getReportYear();
        $this->reportMonths = (new DateReport)->getReportMonths();
    }

    /**
     * Get report for milestones by year.
     */
    public function getReportPerYear(string $year = '2022'): Collection
    {
        $milestonesByMonths = $this->getMilestonesByMonths($year);
        $data = collect([
            'total_milestones_count' => Milestone::whereYear('created_at', '<=', $year)->count(),
            'overdue_milestones_count' => Milestone::whereYear('created_at', '<=', $year)->overdue()->get()->where('progress', '<', 1)->count(),
            'unstarted_milestones_count' => Milestone::whereYear('created_at', '<=', $year)->get()->where('progress', 0)->count(),
            'report_months' => $this->reportMonths,
            'total_milestones_by_month' => $milestonesByMonths,
            'quarterly_created_milestones' => $this->getMilestonesByQuarters($year, $milestonesByMonths),
        ]);

        return $data;
    }

    /**
     * Get milestones count by year
     */
    protected function getMilestonesByMonths(string $year): array
    {
        $milestones = [];

        foreach ($this->reportYear as $quarter) {
            foreach ($quarter as $key => $month) {
                $milestones[$month['index']] = Milestone::whereYear('created_at', $year)->whereMonth('created_at', $key)->count();
            }
        }

        return $milestones;
    }

    /**
     * Get milestones count by quarters year
     */
    protected function getMilestonesByQuarters(string $year, array $milestonesByMonths): array
    {
        $milestonesByQuarters = [];

        foreach ($this->reportYear as $quarter => $months) {
            array_push($milestonesByQuarters, [
                'title' => __('pages.content.dates.' . $quarter) . ', ' . $year,
                'values' => $this->getMilestonesByQuartersMonths($milestonesByMonths, $months)
            ]);
        }

        return $milestonesByQuarters;
    }

    /**
     * Get milestones count by quarters months
     */
    protected function getMilestonesByQuartersMonths(array $milestonesByMonths, array $months)
    {
        $milestones = [];
        $totalCount = 0;

        foreach ($months as $key => $month) {
            $totalCount += $milestonesByMonths[$month['index']];
            array_push($milestones, ['title' => $month['text'], 'value' => $milestonesByMonths[$month['index']]]);
        }

        array_push($milestones, ['title' => __('pages.content.labels.total'), 'value' => $totalCount]);

        return $milestones;
    }
}