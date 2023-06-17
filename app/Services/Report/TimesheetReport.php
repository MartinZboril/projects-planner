<?php

namespace App\Services\Report;

use App\Interfaces\ReportInterface;
use App\Models\Timer;
use Illuminate\Support\Collection;

class TimesheetReport implements ReportInterface
{
    public function __construct(
        public BuilderReport $builderReport = new BuilderReport
    ) {
    }

    /**
     * Get report for timesheets by year.
     */
    public function getReportPerYear(string $year): Collection
    {
        $timersByMonths = $this->getRecordsByMonths($year);
        $data = collect([
            'year' => $year,
            'total_timers_count' => Timer::whereYear('created_at', '<=', $year)->get()->sum('total_time'),
            'report_months' => $this->builderReport->reportMonthsIndexes,
            'total_timers_by_month' => $timersByMonths,
            'quarterly_recorded_timers' => $this->builderReport->getItemsByQuarters($year, $timersByMonths),
        ]);

        return $data;
    }

    /**
     * Get timers count by year
     */
    public function getRecordsByMonths(string $year): Collection
    {
        $timers = collect();

        $this->builderReport->reportMonthsFull->each(function ($month, $key) use ($timers, $year) {
            $timers->put($month['index'], Timer::whereYear('created_at', $year)->whereMonth('created_at', $key + 1)->get()->sum('total_time'));
        });

        return $timers;
    }
}
