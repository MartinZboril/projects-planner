<?php

namespace App\Listeners\Ticket\Activity;

use App\Events\Ticket\TicketDeleted;

class LogTicketDeletedForProjectActivity
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
    public function handle(TicketDeleted $event): void
    {
        activity()
            ->performedOn($event->ticket->project)
            ->log("Ticket {$event->ticket->subject} was deleted for the project");
    }
}
