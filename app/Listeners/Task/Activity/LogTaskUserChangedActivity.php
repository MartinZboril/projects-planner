<?php

namespace App\Listeners\Task\Activity;

use App\Events\Task\TaskUserChanged;

class LogTaskUserChangedActivity
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
    public function handle(TaskUserChanged $event): void
    {
        if (! $event->old_user) {
            return;
        }

        activity()
            ->performedOn($event->task)
            ->log("User was changed from {$event->old_user->full_name} to {$event->user->full_name}");
    }
}
