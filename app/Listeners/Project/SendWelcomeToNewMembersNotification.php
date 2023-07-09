<?php

namespace App\Listeners\Project;

use App\Events\Project\ProjectTeamChanged;
use App\Notifications\Project\UserAssignedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendWelcomeToNewMembersNotification implements ShouldQueue
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
        foreach ($event->project->team as $user) {
            if (! in_array($user->id, $event->old_team->toArray())) {
                $user->notify(new UserAssignedNotification($event->project));
            }
        }
    }
}
