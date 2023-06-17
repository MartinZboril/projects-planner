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
        // Save note
        $rate->fill($inputs)->save();
    }
}
