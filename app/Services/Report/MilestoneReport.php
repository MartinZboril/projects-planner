<?php

namespace App\Services\Report;

use App\Interfaces\ReportInterface;
use App\Models\Milestone;
use Illuminate\Support\Collection;

class MilestoneReport implements ReportInterface
{
    public function __construct(
        public BuilderReport $builderReport = new BuilderReport
    ) {
    }

    /**
     * Get report for milestones by year.
     */
    public function getReportPerYear(string $year): Collection
    {
        $milestonesByMonths = $this->getRecordsByMonths($year);
        $data = collect([
            'year' => $year,
            'total_milestones_count' => Milestone::whereYear('created_at', '<=', $year)->count(),
            'overdue_milestones_count' => Milestone::with('tasks', 'tasksCompleted')->whereYear('created_at', '<=', $year)->overdue()->get()->where('progress', '<', 1)->count(),
            'unstarted_milestones_count' => Milestone::with('tasks', 'tasksCompleted')->whereYear('created_at', '<=', $year)->get()->where('progress', 0)->count(),
            'report_months' => $this->builderReport->reportMonthsIndexes,
            'total_milestones_by_month' => $milestonesByMonths,
            'quarterly_created_milestones' => $this->builderReport->getItemsByQuarters($year, $milestonesByMonths),
        ]);

        return $data;
    }

    /**
     * Get milestones count by year
     */
    public function getRecordsByMonths(string $year): Collection
    {
        $milestones = collect();

        $this->builderReport->reportMonthsFull->each(function ($month, $key) use ($milestones, $year) {
            $milestones->put($month['index'], Milestone::whereYear('created_at', $year)->whereMonth('created_at', $key + 1)->count());
        });

        return $milestones;
    }
}
