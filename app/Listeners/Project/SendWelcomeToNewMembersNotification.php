<?php

namespace App\Listeners\Project;

use App\Events\Project\ProjectTeamChanged;
use App\Models\User;
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
        $event->new_team->each(function (User $user) use ($event) {
            $user->notify(new UserAssignedNotification($event->project));
        });
    }
}
