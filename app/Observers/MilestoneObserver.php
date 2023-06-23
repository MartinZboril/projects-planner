<?php

namespace App\Observers;

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
    }
}
