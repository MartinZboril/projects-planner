<?php

namespace App\Observers;

use App\Models\Task;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\Task\UserDeletedNotification;
use App\Notifications\Ticket\AssigneeDeletedNotification;

class UserObserver
{
    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        $user->avatar()->delete();
        $user->address()->delete();
        $user->notes()->where('is_private', true)->delete();
    }
}
