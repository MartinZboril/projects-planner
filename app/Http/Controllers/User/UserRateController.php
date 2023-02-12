<?php

namespace App\Http\Controllers\User;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Http\Requests\Rate\{StoreRateRequest, UpdateRateRequest};
use App\Models\{Rate, User};
use App\Traits\FlashTrait;
use App\Services\Data\RateService;

class UserRateController extends Controller
{
    use FlashTrait;

    public function __construct(private RateService $rateService)
    {
    }

    /**
     * Show the form for creating a new rate.
     */
    public function create(User $user): View
    {
        return view('users.rates.create', ['user' => $user]);
    }

    /**
     * Store a newly created rate in storage.
     */
    public function store(StoreRateRequest $request, User $user): RedirectResponse
    {
        try {
            $this->rateService->handleSave(new Rate, $request->safe()->merge([
                'user_id' => $user->id
            ]));
            $this->flash(__('messages.rate.create'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
        return $request->has('save_and_close')
            ? redirect()->route('users.index')
            : redirect()->route('users.show', $user);
    }

    /**
     * Show the form for editing the rate.
     */
    public function edit(User $user, Rate $rate): View
    {
        return view('users.rates.edit', ['user' => $user, 'rate' => $rate]);
    }

    /**
     * Update the rate in storage.
     */
    public function update(UpdateRateRequest $request, User $user, Rate $rate): RedirectResponse
    {
        try {
            $this->rateService->handleSave($rate, $request->safe());
            $this->flash(__('messages.rate.update'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
        return $request->has('save_and_close')
            ? redirect()->route('users.index')
            : redirect()->route('users.show', $user);
    }
}
