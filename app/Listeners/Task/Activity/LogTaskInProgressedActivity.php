<?php

namespace App\Listeners\Task\Activity;

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
    public function handle(object $event): void
    {
        activity()
            ->performedOn($event->task)
            ->log('Task was in progressed');
    }
}
