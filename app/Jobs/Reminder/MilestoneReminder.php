<?php

namespace App\Jobs\Reminder;

use App\Models\Milestone;
use App\Notifications\Milestone\MilestoneReminderNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class MilestoneReminder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private Collection $milestones
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->milestones->each(function (Milestone $milestone) {
            $milestone->owner->notify(new MilestoneReminderNotification($milestone));
        });
    }
}
