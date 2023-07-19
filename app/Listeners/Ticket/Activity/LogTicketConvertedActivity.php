<?php

namespace App\Listeners\Ticket\Activity;

use App\Events\Ticket\Status\TicketConverted;

class LogTicketConvertedActivity
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
    public function handle(TicketConverted $event): void
    {
        activity()
            ->performedOn($event->ticket)
            ->log('Ticket was converted into task');
    }
}
