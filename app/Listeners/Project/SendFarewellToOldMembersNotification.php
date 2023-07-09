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
        foreach ($event->old_team as $userId) {
            if (! in_array($userId, $event->new_team->toArray())) {
                User::find($userId)->notify(new UserUnassignedNotification($event->project));
            }
        }
    }
}
