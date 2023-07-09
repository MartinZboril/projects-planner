<?php

namespace App\Listeners\Ticket\Status;

use App\Events\Ticket\Status\TicketReopened;
use App\Notifications\Ticket\Status\ReopenedTicketNotification;

class SendTicketReopenedNotification
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
        $event->ticket->assignee?->notify(new ReopenedTicketNotification($event->ticket));
    }
}
