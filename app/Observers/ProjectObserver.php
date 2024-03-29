<?php

namespace App\Observers;

use App\Models\File;
use App\Models\Project;

class ProjectObserver
{
    /**
     * Handle the Project "deleted" event.
     */
    public function deleted(Project $project): void
    {
        $project->notes()->delete();
        $project->files()->delete();
        File::where('fileable_type', 'App\Models\Comment')->whereIn('fileable_id', array_column($project->comments->toArray(), 'id'))->delete();
        $project->comments()->delete();
    }
}
