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
        $inputs['user_id'] = $rate->user_id ?? $inputs['user_id'];
        $inputs['note'] = $rate->note ?? null;
        // Save note
        $rate->fill($inputs)->save();
    }
}