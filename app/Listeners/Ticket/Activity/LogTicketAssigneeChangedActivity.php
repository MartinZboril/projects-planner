<?php

namespace App\Listeners\Ticket\Activity;

use App\Events\Ticket\TicketAssigneeChanged;

class LogTicketAssigneeChangedActivity
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
        if (! $event->old_assignee) {
            return;
        }

        activity()
            ->performedOn($event->ticket)
            ->log("Assignee was changed from {$event->old_assignee->full_name} to {$event->assignee->full_name}");
    }
}
