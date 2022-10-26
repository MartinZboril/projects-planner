<?php

namespace App\Services;

use App\Models\Rate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class RateService
{
    public function store(Request $request): Rate
    {
        $rate = new Rate;
        $rate->user_id = $request->user_id;
        $rate->name = $request->name;
        $rate->is_active = $request->is_active;
        $rate->value = $request->value;
        $rate->save();

        return $rate;
    }

    public function update(Rate $rate, Request $request): Rate
    {
        Rate::where('id', $rate->id)
                    ->update([
                        'name' => $request->name,
                        'is_active' => $request->is_active,
                        'value' => $request->value,
                    ]);

        return $rate;
    }

    public function flash(string $action): void
    {
        switch ($action) {
            case 'create':
                Session::flash('message', __('messages.rate.create'));
                Session::flash('type', 'info');
                break;
            case 'update':
                Session::flash('message', __('messages.rate.update'));
                Session::flash('type', 'info');
                break;
            default:
                Session::flash('message', __('messages.complete'));
                Session::flash('type', 'info');
        }
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