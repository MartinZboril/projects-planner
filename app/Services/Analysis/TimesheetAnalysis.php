<?php

namespace App\Services\Analysis;

use App\Models\Timer;
use Illuminate\Support\Collection;

class TimesheetAnalysis
{
    /**
     * Get analyze for timesheets.
     */
    public function getAnalyze(): Collection
    {
        return Timer::all();
    }
}