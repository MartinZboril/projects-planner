<?php

namespace App\Http\Controllers\User\Rate;

use App\Http\Controllers\Controller;
use App\Http\Requests\Rate\AssignRate;
use App\Models\User;
use App\Services\Data\RateService;
use App\Traits\FlashTrait;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class UserRateAssignController extends Controller
{
    use FlashTrait;

    public function __construct(
        private RateService $rateService
    ) {
    }

    /**
     * Update the rate in storage.
     */
    public function __invoke(AssignRate $request, User $user): RedirectResponse
    {
        try {
            ($user->rates()->count() === 0) ? $user->rates()->attach($request['rates']) : $user->rates()->sync($request['rates']);
            $this->flash(__('messages.rate.assign'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);

            return redirect()->back()->with(['error' => __('messages.error')]);
        }

        return redirect()->route('users.show', $user);
    }
}
