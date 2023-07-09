<?php

namespace App\Listeners\User;

use App\Events\User\UserDeleted;
use App\Notifications\User\UserDeletedNotification;

class SendUserDeletedNotification
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
    public function handle(UserDeleted $event): void
    {
        $event->user->notify(new UserDeletedNotification($event->user));
    }
}
