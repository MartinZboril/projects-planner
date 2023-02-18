<?php

namespace App\Services\Data;

use Illuminate\Support\ValidatedInput;
use App\Models\Rate;

class RateService
{
    /**
     * Save data for rate.
     */
    public function handleSave(Rate $rate, ValidatedInput $inputs): void
    {
        Rate::updateOrCreate(
            ['id' => $rate->id],
            [
                'user_id' => $rate->user_id ?? $inputs->user_id,
                'name' => $inputs->name,
                'is_active' => $inputs->has('is_active'),
                'value' => $inputs->value,
                'note' => $inputs->note ?? null,
            ]
        );
    }
}