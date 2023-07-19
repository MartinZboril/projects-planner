<?php

namespace App\Listeners\Timer\Activity;

use App\Events\Timer\TimerChanged;

class LogTimerChangedForProjectActivity
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
    public function handle(TimerChanged $event): void
    {
        activity()
            ->performedOn($event->timer->project)
            ->log($event->old_total_time
                        ? "User {$event->timer->user->full_name} changed timer for {$event->timer->rate->name} rate from {$event->old_total_time} hours to {$event->timer->total_time} hours"
                        : "User {$event->timer->user->full_name} added timer with {$event->timer->total_time} hours for {$event->timer->rate->name} rate");
    }
}
