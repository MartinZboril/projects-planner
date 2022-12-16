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
     * Store new rate.
     */
    public function store(ValidatedInput $inputs): Rate
    {
        $rate = new Rate;
        $rate->user_id = $inputs->user_id;

        return $this->save($rate, $inputs);
    }

    /**
     * Update rate.
     */
    public function update(Rate $rate, ValidatedInput $inputs): Rate
    {
        return $this->save($rate, $inputs);
    }

    /**
     * Save data for rate.
     */
    protected function save(Rate $rate, ValidatedInput $inputs)
    {
        $rate->name = $inputs->name;
        $rate->is_active = $inputs->has('is_active');
        $rate->value = $inputs->value;
        $rate->note = $inputs->note;
        $rate->save();

        return $rate;
    }

    /**
     * Set up redirect for the action
     */
    public function setUpRedirect(Rate $rate): RedirectResponse
    {
        return (new RouteService)->redirect(UserRouteEnum::Detail->value, ['user' => $rate->user]);
    }
}