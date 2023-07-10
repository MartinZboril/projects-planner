<?php

namespace App\Jobs\Reminder;

use App\Models\Ticket;
use App\Notifications\Ticket\TicketReminderNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class TicketReminder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private Collection $tickets,
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->tickets->each(function (Ticket $ticket) {
            $ticket->reporter->notify(new TicketReminderNotification($ticket));
            $ticket->assignee ? $ticket->assignee->notify(new TicketReminderNotification($ticket)) : null;
        });
    }
}
