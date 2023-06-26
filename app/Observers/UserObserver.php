<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        $user->address()->delete();
        $user->notes()->where('is_private', true)->delete();
    }
}
