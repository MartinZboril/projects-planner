<?php

namespace App\Listeners\Milestone;

use App\Events\Milestone\MilestoneOwnerChanged;
use App\Models\User;
use App\Notifications\Milestone\OwnerAssignedNotification;
use App\Notifications\Milestone\OwnerUnassignedNotification;

class SendMilestoneAssignmentNotifications
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
    public function handle(MilestoneOwnerChanged $event): void
    {
        $event->milestone->owner->notify(new OwnerAssignedNotification($event->milestone));

        if ($event->old_owner) {
            User::find($event->old_owner)->notify(new OwnerUnassignedNotification($event->milestone));
        }
    }
}
