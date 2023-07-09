<?php

namespace App\Listeners\Ticket;

use App\Events\Ticket\TicketDeleted;
use App\Notifications\Ticket\TicketDeletedNotification;

class SendTicketDeletedNotification
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
        $event->ticket->assignee?->notify(new TicketDeletedNotification($event->ticket));
    }
}
