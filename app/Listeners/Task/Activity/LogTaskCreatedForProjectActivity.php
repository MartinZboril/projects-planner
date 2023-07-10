<?php

namespace App\Listeners\Task\Activity;

use App\Events\Task\TaskCreated;

class LogTaskCreatedForProjectActivity
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
    public function handle(TaskCreated $event): void
    {
        activity()
            ->performedOn($event->task->project)
            ->log("Task {$event->task->name} was created for the project");
    }
}
