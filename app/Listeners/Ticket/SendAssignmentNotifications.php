<?php

namespace App\Listeners\Ticket;

use App\Events\Ticket\TicketAssigneeChanged;
use App\Notifications\Ticket\AssigneeAssignedNotification;
use App\Notifications\Ticket\AssigneeUnassignedNotification;

class SendAssignmentNotifications
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
    public function handle(TicketAssigneeChanged $event): void
    {
        $event->assignee->notify(new AssigneeAssignedNotification($event->ticket));

        if ($event->old_assignee) {
            $event->old_assignee->notify(new AssigneeUnassignedNotification($event->ticket));
        }
    }
}
