<?php

namespace App\Listeners\Task\Activity;

class LogTaskAssignForMilestoneActivity
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
        activity()
            ->performedOn($event->milestone)
            ->log("Task {$event->task->name} has been assigned for the milestone");
    }
}
