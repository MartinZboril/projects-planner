<?php

namespace App\Listeners\Project\Status;

use App\Events\Project\Status\ProjectArchived;
use App\Models\User;
use App\Notifications\Project\Status\ArchivedProjectNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendProjectArchivedNotification implements ShouldQueue
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
    public function handle(ProjectArchived $event): void
    {
        $event->project->team->each(function (User $user) use ($event) {
            $user->notify(new ArchivedProjectNotification($event->project));
        });
    }
}
