<?php

namespace App\Listeners\Task;

use App\Events\Task\TaskUserChanged;
use App\Notifications\Task\UserAssignedNotification;
use App\Notifications\Task\UserUnassignedNotification;

class SendTaskAssignmentNotifications
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
        $event->user->notify(new UserAssignedNotification($event->task));

        if ($event->old_user) {
            $event->old_user->notify(new UserUnassignedNotification($event->task));
        }
    }
}
