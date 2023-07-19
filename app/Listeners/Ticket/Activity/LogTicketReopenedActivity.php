<?php

namespace App\Listeners\Ticket\Activity;

class LogTicketReopenedActivity
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
            ->performedOn($event->ticket)
            ->log('Ticket was reopened');
    }
}
