<?php

namespace App\Services\Report;

use Illuminate\Support\Collection;

class BuilderReport
{
    public $reportYear;

    public $reportMonthsFull;

    public $reportMonthsIndexes;

    public function __construct()
    {
        $this->reportYear = $this->getReportYear();
        $this->reportMonthsFull = $this->getReportMonths(true);
        $this->reportMonthsIndexes = $this->getReportMonths(false);
    }

    /**
     * Get items count by quarters year
     */
    public function getItemsByQuarters(string $year, Collection $itemsByMonths): Collection
    {
        $itemsByQuarters = collect();

        foreach ($this->reportYear as $quarter => $months) {
            $itemsByQuarters->push([
                'title' => __('pages.content.dates.'.$quarter).', '.$year,
                'values' => $this->getItemsByQuartersMonths($itemsByMonths, $months),
            ]);
        }

        return $itemsByQuarters;
    }

    /**
     * Get items count by quarters months
     */
    public function getItemsByQuartersMonths(Collection $itemsByMonths, array $months): Collection
    {
        $items = collect();
        $totalCount = 0;

        foreach ($months as $key => $month) {
            $items = $this->pushValuesToItems($items, $month['text'], $itemsByMonths->get($month['index']));
            $totalCount += $itemsByMonths->get($month['index']);
        }

        $items = $this->pushValuesToItems($items, __('pages.content.labels.total'), $totalCount);

        return $items;
    }

    protected function pushValuesToItems(Collection $items, string $title, float $value): Collection
    {
        $items->push([
            'title' => $title,
            'value' => $value,
        ]);

        return $items;
    }

    public function getReportYear(): array
    {
        return [
            '1_st_quarter' => [
                1 => [
                    'index' => 'Jan',
                    'text' => __('pages.content.dates.months.january'),
                ],
                2 => [
                    'index' => 'Feb',
                    'text' => __('pages.content.dates.months.february'),
                ],
                3 => [
                    'index' => 'Mar',
                    'text' => __('pages.content.dates.months.march'),
                ],
            ],
            '2_nd_quarter' => [
                4 => [
                    'index' => 'Apr',
                    'text' => __('pages.content.dates.months.april'),
                ],
                5 => [
                    'index' => 'May',
                    'text' => __('pages.content.dates.months.may'),
                ],
                6 => [
                    'index' => 'Jun',
                    'text' => __('pages.content.dates.months.june'),
                ],
            ],
            '3_rd_quarter' => [
                7 => [
                    'index' => 'Jul',
                    'text' => __('pages.content.dates.months.july'),
                ],
                8 => [
                    'index' => 'Aug',
                    'text' => __('pages.content.dates.months.august'),
                ],
                9 => [
                    'index' => 'Sep',
                    'text' => __('pages.content.dates.months.september'),
                ],
            ],
            '4_th_quarter' => [
                10 => [
                    'index' => 'Oct',
                    'text' => __('pages.content.dates.months.october'),
                ],
                11 => [
                    'index' => 'Nov',
                    'text' => __('pages.content.dates.months.november'),
                ],
                12 => [
                    'index' => 'Dec',
                    'text' => __('pages.content.dates.months.december'),
                ],
            ],
        ];
    }

    /**
     * Get months of report
     */
    public function getReportMonths(bool $full = false): Collection
    {
        $months = collect();
        $reportYear = $this->reportYear;

        foreach ($reportYear as $quarter) {
            foreach ($quarter as $month) {
                if ($full) {
                    $months->push($month);
                } else {
                    $months->push($month['index']);
                }
            }
        }

        return $months;
    }
}
