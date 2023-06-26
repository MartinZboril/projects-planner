<?php

namespace App\Observers;

use App\Models\Role;

class RoleObserver
{
    /**
     * Handle the Role "deleted" event.
     */
    public function deleted(Role $role): void
    {
        $role->users()->update([
            'role_id' => null,
        ]);
    }
}
