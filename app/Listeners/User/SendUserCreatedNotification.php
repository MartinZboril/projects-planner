<?php

namespace App\Listeners\User;

use App\Events\User\UserCreated;
use App\Notifications\User\UserCreatedNotification;

class SendUserCreatedNotification
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
    public function handle(UserCreated $event): void
    {
        $event->user->notify(new UserCreatedNotification($event->user, $event->password));
    }
}
