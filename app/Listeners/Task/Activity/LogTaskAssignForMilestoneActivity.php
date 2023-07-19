<?php

namespace App\Listeners\Task\Activity;

use App\Events\Task\TaskMilestoneChanged;

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
    public function handle(TaskMilestoneChanged $event): void
    {
        activity()
            ->performedOn($event->milestone)
            ->log("Task {$event->task->name} has been assigned for the milestone");
    }
}
