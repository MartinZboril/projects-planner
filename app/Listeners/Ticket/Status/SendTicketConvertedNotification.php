<?php

namespace App\Listeners\Ticket\Status;

use App\Events\Ticket\Status\TicketConverted;
use App\Notifications\Ticket\Status\TicketConvertedToTaskNotification;

class SendTicketConvertedNotification
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
        $event->ticket->task->user->notify(new TicketConvertedToTaskNotification($event->ticket));
    }
}
