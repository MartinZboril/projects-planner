<?php

namespace App\Services\Data;

use App\Enums\Routes\UserRouteEnum;
use App\Models\Rate;
use App\Services\RouteService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\ValidatedInput;

class RateService
{
    /**
     * Save data for rate.
     */
    public function save(Rate $rate, ValidatedInput $inputs): Rate
    {
        return Rate::updateOrCreate(
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

    /**
     * Set up redirect for the action.
     */
    public function setUpRedirect(Rate $rate): RedirectResponse
    {
        return (new RouteService)->redirect(UserRouteEnum::Detail->value, ['user' => $rate->user]);
    }
}