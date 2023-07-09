<?php

namespace App\Listeners\Ticket;

use App\Models\Ticket;
use App\Events\User\UserDeleted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\Ticket\AssigneeDeletedNotification;

class SendTicketsWithoutAssigneeNotification implements ShouldQueue
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
    public function handle(UserDeleted $event): void
    {
        $event->user->tickets->each(function (Ticket $ticket) {
            $ticket->reporter->notify(new AssigneeDeletedNotification($ticket));
        });
    }
}
