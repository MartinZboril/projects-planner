<?php

namespace App\Services\Report;

class DateReport
{
    public function getReportMonths(): array
    {
        $months = [];
        $reportYear = $this->getReportYear();

        foreach ($reportYear as $quarter) {
            foreach ($quarter as $month) {
                array_push($months, $month['index']);
            }
        }

        return $months;
    }

    public function getReportYear(): array
    {
        return [
            '1_st_quarter' => [
                1 => [
                    'index' => 'Jan',
                    'text' => __('pages.content.dates.months.january')
                ],
                2 => [
                    'index' => 'Feb',
                    'text' => __('pages.content.dates.months.february')
                ],
                3 => [
                    'index' => 'Mar',
                    'text' => __('pages.content.dates.months.march')
                ]
            ],
            '2_nd_quarter' => [
                4 => [
                    'index' => 'Apr',
                    'text' => __('pages.content.dates.months.april')
                ],
                5 => [
                    'index' => 'May',
                    'text' => __('pages.content.dates.months.may')
                ],
                6 => [
                    'index' => 'Jun',
                    'text' => __('pages.content.dates.months.june')
                ]
            ],
            '3_rd_quarter' => [
                7 => [
                    'index' => 'Jul',
                    'text' => __('pages.content.dates.months.july')
                ],
                8 => [
                    'index' => 'Aug',
                    'text' => __('pages.content.dates.months.august')
                ],
                9 => [
                    'index' => 'Sep',
                    'text' => __('pages.content.dates.months.september')
                ]
            ],
            '4_th_quarter' => [
                10 => [
                    'index' => 'Oct',
                    'text' => __('pages.content.dates.months.october')
                ],
                11 => [
                    'index' => 'Nov',
                    'text' => __('pages.content.dates.months.november')
                ],
                12 => [
                    'index' => 'Dec',
                    'text' => __('pages.content.dates.months.december')
                ]
            ]
        ];
    }
}