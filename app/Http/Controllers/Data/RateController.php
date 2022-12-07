<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Http\Requests\Rate\{StoreRateRequest, UpdateRateRequest};
use App\Models\{Rate, User};
use App\Services\FlashService;
use App\Services\Data\RateService;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RateController extends Controller
{
    protected $rateService;
    protected $flashService;

    public function __construct(RateService $rateService, FlashService $flashService)
    {
        $this->middleware('auth');
        $this->rateService = $rateService;
        $this->flashService = $flashService;
    }

    /**
     * Show the form for creating a new rate.
     */
    public function create(User $user): View
    {
        return view('rates.create', ['user' => $user, 'rate' => new Rate]);
    }

    /**
     * Store a newly created rate in storage.
     */
    public function store(StoreRateRequest $request): RedirectResponse
    {
        try {
            $rate = $this->rateService->store($request->safe());
            $this->flashService->flash(__('messages.rate.create'), 'info');

            return $this->rateService->redirect('user', $rate); 
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
    }

    /**
     * Show the form for editing the rate.
     */
    public function edit(User $user, Rate $rate): View
    {
        return view('rates.edit', ['user' => $user, 'rate' => $rate]);
    }

    /**
     * Update the rate in storage.
     */
    public function update(UpdateRateRequest $request, Rate $rate): RedirectResponse
    {
        try {
            $rate = $this->rateService->update($rate, $request->safe());
            $this->flashService->flash(__('messages.rate.update'), 'info');

            return $this->rateService->redirect('user', $rate); 
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
    }
}
