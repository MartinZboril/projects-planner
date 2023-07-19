<?php

namespace App\Listeners\Timer\Activity;

use App\Events\Timer\TimerStopped;

class LogTimerStoppedForProjectActivity
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
    public function handle(TimerStopped $event): void
    {
        activity()
            ->performedOn($event->timer->project)
            ->log("User {$event->timer->user->full_name} worked {$event->timer->total_time} hours for {$event->timer->rate->name} rate");
    }
}
