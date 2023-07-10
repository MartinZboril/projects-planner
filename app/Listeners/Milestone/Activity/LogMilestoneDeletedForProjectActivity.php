<?php

namespace App\Listeners\Milestone\Activity;

use App\Events\Milestone\MilestoneDeleted;

class LogMilestoneDeletedForProjectActivity
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
    public function handle(MilestoneDeleted $event): void
    {
        activity()
            ->performedOn($event->milestone->project)
            ->log("Milestone {$event->milestone->subject} was deleted for the project");
    }
}
