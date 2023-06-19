<?php

namespace App\Services\Data;

use App\Models\Role;

class RoleService
{
    /**
     * Save data for role.
     */
    public function handleSave(Role $role, array $inputs): void
    {
        // Prepare fields
        $inputs['is_active'] = $inputs['is_active'] ?? false;
        // Save role
        $role->fill($inputs)->save();
        // Assign permissions
        $inputs['permissions'] = $inputs['permissions'] ?? [];
        $this->handleAssignPermissions($role, $inputs);
    }

    /**
     * Assign permissions to role.
     */
    public function handleAssignPermissions(Role $role, array $inputs): void
    {
        ($role->permissions()->count() === 0) ? $role->permissions()->attach($inputs['permissions']) : $role->permissions()->sync($inputs['permissions']);
    }
}
