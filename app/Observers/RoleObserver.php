<?php

namespace App\Observers;

use App\Models\PermissionRole;
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
        PermissionRole::where('role_id', $role->id)->delete();
    }
}
