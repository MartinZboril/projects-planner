<?php

namespace App\Services\Data;

use App\Models\Rate;
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
        $rate->save();

        return $rate;
    }

    /**
     * Get route for the action
     */
    public function redirect(string $action, Rate $rate): RedirectResponse 
    {   
        switch ($action) {
            case 'user':
                return redirect()->route('users.detail', ['user' => $rate->user]);
                break;
            default:
                return redirect()->back();
        }
    }
}