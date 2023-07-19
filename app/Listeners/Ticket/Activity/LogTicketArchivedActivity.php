<?php

namespace App\Listeners\Ticket\Activity;

use App\Events\Ticket\Status\TicketArchived;

class LogTicketArchivedActivity
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
    public function handle(TicketArchived $event): void
    {
        activity()
            ->performedOn($event->ticket)
            ->log('Ticket was archived');
    }
}
