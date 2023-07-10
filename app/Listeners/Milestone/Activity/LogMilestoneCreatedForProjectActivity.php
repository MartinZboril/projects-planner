<?php

namespace App\Listeners\Milestone\Activity;

use App\Events\Milestone\MilestoneCreated;

class LogMilestoneCreatedForProjectActivity
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
    public function handle(MilestoneCreated $event): void
    {
        activity()
            ->performedOn($event->milestone->project)
            ->log("Milestone {$event->milestone->name} was created for the project");
    }
}
