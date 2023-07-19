<?php

namespace App\Listeners\Task\Activity;

use App\Events\Task\Status\TaskPaused;

class LogTaskPausedActivity
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
    public function handle(TaskPaused $event): void
    {
        activity()
            ->performedOn($event->task)
            ->log('Task was paused');
    }
}
