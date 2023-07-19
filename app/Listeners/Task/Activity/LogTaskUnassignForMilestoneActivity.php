<?php

namespace App\Listeners\Task\Activity;

use App\Events\Task\TaskMilestoneChanged;

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
    public function handle(TaskMilestoneChanged $event): void
    {
        if (! $event->old_milestone) {
            return;
        }

        activity()
            ->performedOn($event->old_milestone)
            ->log("Task {$event->task->name} has been unassigned for the milestone");
    }
}
