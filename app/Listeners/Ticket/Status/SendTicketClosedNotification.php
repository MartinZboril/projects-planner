<?php

namespace App\Listeners\Ticket\Status;

use App\Events\Ticket\Status\TicketClosed;
use App\Notifications\Ticket\Status\ClosedTicketNotification;

class SendTicketClosedNotification
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
        $event->ticket->reporter->notify(new ClosedTicketNotification($event->ticket));
    }
}
