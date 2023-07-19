<?php

namespace App\Listeners\Task\Activity;

use App\Events\Task\Status\TaskInProgressed;

class LogTaskInProgressedActivity
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
    public function handle(TaskInProgressed $event): void
    {
        activity()
            ->performedOn($event->task)
            ->log('Task was in progressed');
    }
}
