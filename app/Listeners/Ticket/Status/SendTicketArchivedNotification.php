<?php

namespace App\Listeners\Ticket\Status;

use App\Events\Ticket\Status\TicketArchived;
use App\Notifications\Ticket\Status\ArchivedTicketNotification;

class SendTicketArchivedNotification
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
        $event->ticket->assignee?->notify(new ArchivedTicketNotification($event->ticket));
    }
}
