<?php

namespace App\Services\Report;

use App\Models\Timer;
use Illuminate\Support\Collection;

class TimesheetReport
{
    protected $builderReport;
    
    public function __construct()
    {
        $this->builderReport = new BuilderReport;
    }

    /**
     * Get report for timesheets by year.
     */
    public function getReportPerYear(string $year): Collection
    {
        $timersByMonths = $this->getTimersByMonths($year);
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
    protected function getTimersByMonths(string $year): Collection
    {
        $timers = collect();

        $this->builderReport->reportMonthsFull->each(function ($month, $key) use($timers, $year) {
            $timers->put($month['index'], Timer::whereYear('created_at', $year)->whereMonth('created_at', $key)->get()->sum('total_time'));
        });

        return $timers;
    }
}