<?php

namespace App\Listeners\Project\Status;

use App\Events\Project\Status\ProjectReactived;
use App\Models\User;
use App\Notifications\Project\Status\ReactivedProjectNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendProjectReactivedNotification implements ShouldQueue
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
    public function handle(ProjectReactived $event): void
    {
        $event->project->team->each(function (User $user) use ($event) {
            $user->notify(new ReactivedProjectNotification($event->project));
        });
    }
}
