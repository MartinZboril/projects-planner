<?php

namespace App\Services\Data;

use App\Enums\Routes\ProjectRouteEnum;
use App\Models\Timer;
use App\Services\RouteService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ValidatedInput;

class TimerService
{
    /**
     * Save data for rate.
     */
    public function save(Timer $timer, ValidatedInput $inputs)
    {
        return Timer::updateOrCreate(
            ['id' => $timer->id],
            [
                'project_id' => $timer->project_id ?? $inputs->project_id,
                'user_id' => $timer->user_id ?? Auth::id(),
                'rate_id' => $inputs->rate_id,
                'since' => $inputs->since,
                'until' => $inputs->until,
                'note' => $inputs->note,
            ]
        );
    }

    /**
     * Start measure new timer.
     */
    public function start(ValidatedInput $inputs): void
    {
        Timer::create([
            'project_id' => $inputs->project_id,
            'user_id' => Auth::id(),
            'rate_id' => $inputs->rate_id,
            'since' => now(),
            'until' => null,
        ]);
    }

    /**
     * Stop measure the timer.
     */
    public function stop(Timer $timer): void
    {
        $timer->update(['until' => now()]);
    }

    /**
     * Set up redirect for the action.
     */
    public function setUpRedirect(Timer $timer): RedirectResponse
    {
        return (new RouteService)->redirect(ProjectRouteEnum::Timesheets->value, ['project' => $timer->project]);
    }
}