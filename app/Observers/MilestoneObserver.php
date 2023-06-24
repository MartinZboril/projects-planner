<?php

namespace App\Observers;

use App\Models\File;
use App\Models\Milestone;

class MilestoneObserver
{
    /**
     * Handle the Milestone "deleted" event.
     */
    public function deleted(Milestone $milestone): void
    {
        $milestone->tasks()->update([
            'milestone_id' => null,
        ]);
        $milestone->files()->delete();
        File::where('fileable_type', 'App\Models\Comment')->whereIn('fileable_id', array_column($milestone->comments->toArray(), 'id'))->delete();
    }
}
