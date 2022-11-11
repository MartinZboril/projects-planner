<?php

namespace App\Services\Analysis;

use App\Models\Project;
use Illuminate\Support\Collection;

class ProjectAnalysis
{
    /**
     * Get analyze for projects.
     */
    public function getAnalyze(): Collection
    {
        return Project::all();
    }
}