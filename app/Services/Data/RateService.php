<?php

namespace App\Services\Data;

use App\Models\Rate;
use Illuminate\Http\RedirectResponse;

class RateService
{
    public function store(array $fields): Rate
    {
        $rate = new Rate;
        $rate->user_id = $fields['user_id'];
        $rate->name = $fields['name'];
        $rate->is_active = isset($fields['is_active']) ? 1 : 0;
        $rate->value = $fields['value'];
        $rate->save();

        return $rate;
    }

    public function update(Rate $rate, array $fields): Rate
    {
        Rate::where('id', $rate->id)
                    ->update([
                        'name' => $fields['name'],
                        'is_active' => isset($fields['is_active']) ? 1 : 0,
                        'value' => $fields['value'],
                    ]);

        return $rate;
    }

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