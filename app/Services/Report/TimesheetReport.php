<?php

namespace App\Services\Report;

use App\Models\Timer;
use Illuminate\Support\Collection;

class TimesheetReport
{
    protected $reportYear;
    protected $reportMonths;

    public function __construct()
    {
        $this->reportYear = (new DateReport)->getReportYear();
        $this->reportMonths = (new DateReport)->getReportMonths();
    }

    /**
     * Get report for timesheets by year.
     */
    public function getReportPerYear(string $year = '2022'): Collection
    {
        $timersByMonths = $this->getTimersByMonths($year);
        $data = collect([
            'total_timers_count' => Timer::whereYear('created_at', $year)->get()->sum('total_time'),
            'report_months' => $this->reportMonths,
            'total_timers_by_month' => $timersByMonths,
            'quarterly_recorded_timers' => $this->getTimersByQuarters($year, $timersByMonths),
        ]);

        return $data;
    }

    /**
     * Get timers count by year
     */
    protected function getTimersByMonths(string $year): array
    {
        $timers = [];

        foreach ($this->reportYear as $quarter) {
            foreach ($quarter as $key => $month) {
                $timers[$month['index']] = Timer::whereYear('created_at', $year)->whereMonth('created_at', $key)->get()->sum('total_time');
            }
        }

        return $timers;
    }

    /**
     * Get timers count by quarters year
     */
    protected function getTimersByQuarters(string $year, array $timersByMonths): array
    {
        $timersByQuarters = [];

        foreach ($this->reportYear as $quarter => $months) {
            array_push($timersByQuarters, [
                'title' => __('pages.content.dates.' . $quarter) . ', ' . $year,
                'values' => $this->getTimersByQuartersMonths($timersByMonths, $months)
            ]);
        }

        return $timersByQuarters;
    }

    /**
     * Get timers count by quarters months
     */
    protected function getTimersByQuartersMonths(array $timersByMonths, array $months)
    {
        $timers = [];
        $totalCount = 0;

        foreach ($months as $key => $month) {
            $totalCount += $timersByMonths[$month['index']];
            array_push($timers, ['title' => $month['text'], 'value' => $timersByMonths[$month['index']]]);
        }

        array_push($timers, ['title' => __('pages.content.labels.total'), 'value' => $totalCount]);

        return $timers;
    }
}