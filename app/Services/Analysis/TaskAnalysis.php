<?php

namespace App\Services\Analysis;

use App\Models\Task;
use Illuminate\Support\Collection;

class TaskAnalysis
{
    /**
     * Get analyze for tasks.
     */
    public function getAnalyze(): Collection
    {
        return Task::all();
    }
}