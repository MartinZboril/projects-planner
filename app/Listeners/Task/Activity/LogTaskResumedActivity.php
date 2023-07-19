<?php

namespace App\Listeners\Task\Activity;

use App\Events\Task\Status\TaskResumed;

class LogTaskResumedActivity
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
    public function handle(TaskResumed $event): void
    {
        activity()
            ->performedOn($event->task)
            ->log('Task was resumed');
    }
}
