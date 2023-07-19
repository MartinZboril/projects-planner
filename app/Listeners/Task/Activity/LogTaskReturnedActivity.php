<?php

namespace App\Listeners\Task\Activity;

use App\Events\Task\Status\TaskReturned;

class LogTaskReturnedActivity
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
    public function handle(TaskReturned $event): void
    {
        activity()
            ->performedOn($event->task)
            ->log('Task was returned');
    }
}
