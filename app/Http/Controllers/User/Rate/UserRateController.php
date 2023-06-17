<?php

namespace App\Http\Controllers\User\Rate;

use App\DataTables\RatesDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Rate\StoreRateRequest;
use App\Http\Requests\Rate\UpdateRateRequest;
use App\Models\Rate;
use App\Services\Data\RateService;
use App\Traits\FlashTrait;
use Exception;
use Illuminate\Http\JsonResponse;
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
     * Display a listing of the users.
     */
    public function index(RatesDataTable $ratesDataTable): JsonResponse|View
    {
        return $ratesDataTable->render('users.rates.index');
    }

    /**
     * Show the form for creating a new rate.
     */
    public function create(): View
    {
        return view('users.rates.create');
    }

    /**
     * Store a newly created rate in storage.
     */
    public function store(StoreRateRequest $request): RedirectResponse
    {
        try {
            $this->rateService->handleSave(new Rate, $request->validated());
            $this->flash(__('messages.rate.create'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);

            return redirect()->back()->with(['error' => __('messages.error')]);
        }

        return redirect()->route('users.rates.index');
    }

    /**
     * Show the form for editing the rate.
     */
    public function edit(Rate $rate): View
    {
        return view('users.rates.edit', ['rate' => $rate]);
    }

    /**
     * Update the rate in storage.
     */
    public function update(UpdateRateRequest $request, Rate $rate): RedirectResponse
    {
        try {
            $this->rateService->handleSave($rate, $request->validated());
            $this->flash(__('messages.rate.update'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);

            return redirect()->back()->with(['error' => __('messages.error')]);
        }

        return redirect()->route('users.rates.index');
    }
}
