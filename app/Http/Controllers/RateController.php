<?php

namespace App\Http\Controllers;

use App\Http\Requests\Rate\{StoreRateRequest, UpdateRateRequest};
use App\Models\{Rate, User};
use App\Services\FlashService;
use App\Services\Data\RateService;
use Exception;
use Illuminate\Support\Facades\Log;

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
     * Show the form for creating a new resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function create(User $user)
    {
        return view('rates.create', ['user' => $user]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRateRequest $request)
    {
        try {
            $fields = $request->validated();
            $rate = $this->rateService->store($fields);
            $this->flashService->flash(__('messages.rate.create'), 'info');

            return $this->rateService->redirect('user', $rate); 
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     * 
     * @param  \App\Models\User  $user
     * @param  \App\Models\Rate  $rate
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user, Rate $rate)
    {
        return view('rates.edit', ['user' => $user, 'rate' => $rate]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRateRequest $request, Rate $rate)
    {
        try {
            $fields = $request->validated();
            $rate = $this->rateService->update($rate, $fields);
            $this->flashService->flash(__('messages.rate.update'), 'info');

            return $this->rateService->redirect('user', $rate); 
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Rate  $rate
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rate $rate)
    {
        //
    }
}
