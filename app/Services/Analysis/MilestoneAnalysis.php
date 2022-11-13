<?php

namespace App\Services\Analysis;

use App\Models\Milestone;
use Illuminate\Support\Collection;

class MilestoneAnalysis
{
    /**
     * Get analyze for milestones.
     */
    public function getAnalyze(): Collection
    {
        return Milestone::all();
    }
}