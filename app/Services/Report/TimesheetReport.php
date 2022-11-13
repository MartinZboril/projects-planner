<?php

namespace App\Services\Report;

use App\Models\Timer;
use Illuminate\Support\Collection;

class TimesheetReport
{
    /**
     * Get report for timesheets by year.
     */
    public function getReportPerYear(string $year = '2022'): Collection
    {
        $data = collect([
            'total_timesheets_count' => Timer::whereYear('created_at', $year)->get()->sum('total_time'),
            'report_months' => sprintf("'%s'", implode("','", $this->getReportMonths())),
            'total_timesheets_by_month' => $this->getTimesheetsByMonths($year),
            'quarterly_created_timesheets' => [
                1 => (
                    Timer::whereYear('created_at', $year)->whereMonth('created_at', 1)->get()->sum('total_time') +
                    Timer::whereYear('created_at', $year)->whereMonth('created_at', 2)->get()->sum('total_time') +
                    Timer::whereYear('created_at', $year)->whereMonth('created_at', 3)->get()->sum('total_time')
                ),
                2 => (
                    Timer::whereYear('created_at', $year)->whereMonth('created_at', 4)->get()->sum('total_time') +
                    Timer::whereYear('created_at', $year)->whereMonth('created_at', 5)->get()->sum('total_time') +
                    Timer::whereYear('created_at', $year)->whereMonth('created_at', 6)->get()->sum('total_time')
                ),
                3 => (
                    Timer::whereYear('created_at', $year)->whereMonth('created_at', 7)->get()->sum('total_time') +
                    Timer::whereYear('created_at', $year)->whereMonth('created_at', 8)->get()->sum('total_time') +
                    Timer::whereYear('created_at', $year)->whereMonth('created_at', 9)->get()->sum('total_time')
                ),
                4 => (
                    Timer::whereYear('created_at', $year)->whereMonth('created_at', 10)->get()->sum('total_time') +
                    Timer::whereYear('created_at', $year)->whereMonth('created_at', 11)->get()->sum('total_time') +
                    Timer::whereYear('created_at', $year)->whereMonth('created_at', 12)->get()->sum('total_time')
                ),
            ]
        ]);

        return $data;
    }

    /**
     * Get timesheets count by year
     */
    protected function getTimesheetsByMonths(string $year): array
    {
        $timesheets = [];
        $months = $this->getReportMonths();

        foreach ($months as $key => $month) {
            $timesheets[$month] = Timer::whereYear('created_at', $year)->whereMonth('created_at', $key)->get()->sum('total_time');
        }

        return $timesheets;
    }

    protected function getReportMonths(): array
    {
        return [1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec'];
    }
}