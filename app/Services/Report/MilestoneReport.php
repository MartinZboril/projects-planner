<?php

namespace App\Services\Report;

use App\Models\Milestone;
use Illuminate\Support\Collection;

class MilestoneReport
{
    /**
     * Get report for milestones by year.
     */
    public function getReportPerYear(string $year = '2022'): Collection
    {
        $data = collect([
            'total_milestones_count' => Milestone::whereYear('created_at', $year)->count(),
            'overdue_milestones_count' => Milestone::whereYear('created_at', $year)->overdue()->get()->where('progress', '<', 1)->count(),
            'unstarted_milestones_count' => Milestone::whereYear('created_at', $year)->get()->where('progress', 0)->count(),
            'report_months' => sprintf("'%s'", implode("','", $this->getReportMonths())),
            'total_milestones_by_month' => $this->getMilestonesByMonths($year),
            'quarterly_created_milestones' => [
                1 => (
                    Milestone::whereYear('created_at', $year)->whereMonth('created_at', 1)->count() +
                    Milestone::whereYear('created_at', $year)->whereMonth('created_at', 2)->count() +
                    Milestone::whereYear('created_at', $year)->whereMonth('created_at', 3)->count()
                ),
                2 => (
                    Milestone::whereYear('created_at', $year)->whereMonth('created_at', 4)->count() +
                    Milestone::whereYear('created_at', $year)->whereMonth('created_at', 5)->count() +
                    Milestone::whereYear('created_at', $year)->whereMonth('created_at', 6)->count()
                ),
                3 => (
                    Milestone::whereYear('created_at', $year)->whereMonth('created_at', 7)->count() +
                    Milestone::whereYear('created_at', $year)->whereMonth('created_at', 8)->count() +
                    Milestone::whereYear('created_at', $year)->whereMonth('created_at', 9)->count()
                ),
                4 => (
                    Milestone::whereYear('created_at', $year)->whereMonth('created_at', 10)->count() +
                    Milestone::whereYear('created_at', $year)->whereMonth('created_at', 11)->count() +
                    Milestone::whereYear('created_at', $year)->whereMonth('created_at', 12)->count()
                ),
            ]
        ]);

        return $data;
    }

    /**
     * Get milestones count by year
     */
    protected function getMilestonesByMonths(string $year): array
    {
        $milestones = [];
        $months = $this->getReportMonths();

        foreach ($months as $key => $month) {
            $milestones[$month] = Milestone::whereYear('created_at', $year)->whereMonth('created_at', $key)->count();
        }

        return $milestones;
    }

    protected function getReportMonths(): array
    {
        return [1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec'];
    }
}