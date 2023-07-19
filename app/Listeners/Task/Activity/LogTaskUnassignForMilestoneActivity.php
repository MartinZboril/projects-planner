<?php

namespace App\Listeners\Task\Activity;

class LogTaskUnassignForMilestoneActivity
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
    public function handle(object $event): void
    {
        if (! $event->old_milestone) {
            return;
        }

        activity()
            ->performedOn($event->old_milestone)
            ->log("Task {$event->task->name} has been unassigned for the milestone");
    }
}
