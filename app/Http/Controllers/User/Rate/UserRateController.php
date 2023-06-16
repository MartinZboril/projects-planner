<?php

namespace App\Http\Controllers\User\Rate;

use App\Http\Controllers\Controller;
use App\Http\Requests\Rate\StoreRateRequest;
use App\Http\Requests\Rate\UpdateRateRequest;
use App\Models\Rate;
use App\Models\User;
use App\Services\Data\RateService;
use App\Traits\FlashTrait;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class UserRateController extends Controller
{
    use FlashTrait;

    public function __construct(
        private RateService $rateService
    ) {
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
            $this->rateService->handleSave(new Rate, $request->validated() + [
                'user_id' => $user->id,
            ]);
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
        return view('users.rates.edit', ['rate' => $rate]);
    }

    /**
     * Update the rate in storage.
     */
    public function update(UpdateRateRequest $request, User $user, Rate $rate): RedirectResponse
    {
        try {
            $this->rateService->handleSave($rate, $request->validated());
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
