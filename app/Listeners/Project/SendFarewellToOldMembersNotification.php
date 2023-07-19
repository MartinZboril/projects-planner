<?php

namespace App\Listeners\Project;

use App\Events\Project\ProjectTeamChanged;
use App\Models\User;
use App\Notifications\Project\UserUnassignedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendFarewellToOldMembersNotification implements ShouldQueue
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
    public function handle(ProjectTeamChanged $event): void
    {
        $event->old_team->each(function (User $user) use ($event) {
            $user->notify(new UserUnassignedNotification($event->project));
        });
    }
}
