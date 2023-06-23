<?php

namespace App\Services\Data;

use App\Models\Timer;
use Illuminate\Support\Facades\Auth;

class TimerService
{
    /**
     * Save data for rate.
     */
    public function handleSave(Timer $timer, array $inputs): Timer
    {
        // Prepare fields
        $inputs['project_id'] = $timer->project_id ?? $inputs['project_id'];
        $inputs['user_id'] = $timer->user_id ?? Auth::id();
        // Save timer
        $timer->fill($inputs)->save();

        return $timer;
    }

    /**
     * Start measure new timer.
     */
    public function handleStart(int $projectId, int $rateId): Timer
    {
        return Timer::create([
            'project_id' => $projectId,
            'user_id' => Auth::id(),
            'rate_id' => $rateId,
            'since_at' => now(),
            'until_at' => null,
        ]);
    }

    /**
     * Stop measure the timer.
     */
    public function handleStop(Timer $timer): void
    {
        $timer->update(['until_at' => now()]);
    }

    /**
     * Delete selected timer.
     */
    public function handleDelete(Timer $timer): void
    {
        $timer->delete();
    }
}
