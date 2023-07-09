<?php

namespace App\Listeners\Project;

use App\Events\Project\ProjectDeleted;
use App\Models\User;
use App\Notifications\Project\ProjectDeletedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendProjectDeletedNotification implements ShouldQueue
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
    public function handle(ProjectDeleted $event): void
    {
        $event->project->team->each(function (User $user) use ($event) {
            $user->notify(new ProjectDeletedNotification($event->project));
        });
    }
}
