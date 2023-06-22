<?php

namespace App\Services\Data;

use App\Models\Rate;

class RateService
{
    /**
     * Save data for rate.
     */
    public function handleSave(Rate $rate, array $inputs): void
    {
        // Prepare fields
        $inputs['is_active'] = $inputs['is_active'] ?? false;
        // Save rate
        $rate->fill($inputs)->save();
        // Assign users
        $inputs['users'] = $inputs['users'] ?? [];
        $this->handleAssignUsers($rate, $inputs);
    }

    /**
     * Assign users to rate.
     */
    public function handleAssignUsers(Rate $rate, array $inputs): void
    {
        ($rate->users()->count() === 0) ? $rate->users()->attach($inputs['users']) : $rate->users()->sync($inputs['users']);
    }
}
