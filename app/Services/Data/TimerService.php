<?php

namespace App\Services\Data;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ValidatedInput;
use App\Models\Timer;

class TimerService
{
    /**
     * Save data for rate.
     */
    public function handleSave(Timer $timer, ValidatedInput $inputs)
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
    public function handleStart(ValidatedInput $inputs): void
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
    public function handleStop(Timer $timer): void
    {
        $timer->update(['until' => now()]);
    }
}