<?php

namespace App\Listeners\Task\Activity;

use App\Events\Task\TaskDeleted;

class LogTaskDeletedForProjectActivity
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
    public function handle(TaskDeleted $event): void
    {
        activity()
            ->performedOn($event->task->project)
            ->log("Task {$event->task->name} was deleted for the project");
    }
}
