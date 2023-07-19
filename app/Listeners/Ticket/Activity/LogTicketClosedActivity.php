<?php

namespace App\Listeners\Ticket\Activity;

use App\Events\Ticket\Status\TicketClosed;

class LogTicketClosedActivity
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
    public function handle(TicketClosed $event): void
    {
        activity()
            ->performedOn($event->ticket)
            ->log('Ticket was closed');
    }
}
