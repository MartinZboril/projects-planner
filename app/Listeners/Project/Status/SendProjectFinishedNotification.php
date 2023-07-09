<?php

namespace App\Listeners\Project\Status;

use App\Events\Project\Status\ProjectFinished;
use App\Models\User;
use App\Notifications\Project\Status\FinishedProjectNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendProjectFinishedNotification implements ShouldQueue
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
    public function handle(ProjectFinished $event): void
    {
        $event->project->team->each(function (User $user) use ($event) {
            $user->notify(new FinishedProjectNotification($event->project));
        });
    }
}
