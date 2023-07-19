<?php

namespace App\Listeners\Ticket\Activity;

use App\Events\Ticket\TicketCreated;

class LogTicketCreatedForProjectActivity
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
    public function handle(TicketCreated $event): void
    {
        activity()
            ->performedOn($event->ticket->project)
            ->log("Ticket {$event->ticket->subject} was created for the project");
    }
}
