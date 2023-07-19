<?php

namespace App\Listeners\Milestone\Activity;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\Milestone\MilestoneOwnerChanged;

class LogMilestoneOwnerChangedActivity
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(MilestoneOwnerChanged $event): void
    {
        if (! $event->old_owner) {
            return;
        }

        activity()
            ->performedOn($event->milestone)
            ->log("Owner was changed from {$event->old_owner->full_name} to {$event->owner->full_name}");
    }
}
