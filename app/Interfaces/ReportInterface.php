<?php

namespace App\Interfaces;

use Illuminate\Support\Collection;

interface ReportInterface
{
    public function getReportPerYear(string $year): Collection;
    public function getRecordsByMonths(string $year): Collection;
}