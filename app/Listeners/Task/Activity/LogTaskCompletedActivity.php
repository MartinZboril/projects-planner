<?php

namespace App\Listeners\Task\Activity;

use App\Events\Task\Status\TaskCompleted;

class LogTaskCompletedActivity
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
    public function handle(TaskCompleted $event): void
    {
        activity()
            ->performedOn($event->task)
            ->log('Task was completed');
    }
}
