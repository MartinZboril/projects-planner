<?php

namespace App\Http\Controllers;

use App\Http\Requests\Rate\{StoreRateRequest, UpdateRateRequest};
use App\Models\Rate;
use App\Models\User;
use App\Services\RateService;

class RateController extends Controller
{
    protected $rateService;

    public function __construct(RateService $rateService)
    {
        $this->middleware('auth');
        $this->rateService = $rateService;
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
        $fields = $request->validated();
        $rate = $this->rateService->store($fields);
        $this->rateService->flash('create');

        return $this->rateService->redirect('user', $rate); 
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
        $fields = $request->validated();
        $rate = $this->rateService->update($rate, $fields);
        $this->rateService->flash('update');

        return $this->rateService->redirect('user', $rate); 
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
