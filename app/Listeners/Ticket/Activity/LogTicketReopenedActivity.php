<?php

namespace App\Listeners\Ticket\Activity;

use App\Events\Ticket\Status\TicketReopened;

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
    public function handle(TicketReopened $event): void
    {
        activity()
            ->performedOn($event->ticket)
            ->log('Ticket was reopened');
    }
}
