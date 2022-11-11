<?php

namespace App\Services\Data;

use App\Models\Timer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class TimerService
{
    /**
     * Store new timer.
     */
    public function store(array $fields): Timer
    {
        $timer = new Timer;
        $timer->project_id = $fields['project_id'];
        $timer->user_id = Auth::id();
        $timer->rate_id = $fields['rate_id'];
        $timer->since = $fields['since'];
        $timer->until = $fields['until'];
        $timer->save();

        return $timer;
    }

    /**
     * Update timer.
     */
    public function update(Timer $timer, array $fields): Timer
    {
        Timer::where('id', $timer->id)
                    ->update([
                        'rate_id' => $fields['rate_id'],
                        'since' => $fields['since'],
                        'until' => $fields['until'],
                    ]);

        return $timer;
    }

    /**
     * Start measure new timer
     */
    public function start(array $fields): Timer
    {
        $timer = new Timer;
        $timer->project_id = $fields['project_id'];
        $timer->rate_id = $fields['rate_id'];
        $timer->user_id = Auth::id();
        $timer->since = now();
        $timer->until = null;
        $timer->save();

        return $timer;
    }

    /**
     * Stop measure the timer
     */
    public function stop(Timer $timer): Timer
    {
        Timer::where('id', $timer->id)
                    ->update([
                        'until' => now(),
                    ]);

        return $timer;
    }

    /**
     * Check if not running another timer of user in project
     */
    public function checkIfNotRunningAnoutherTimer(int $projectId, int $userId): bool
    {
        if(Timer::where('project_id', $projectId)->where('user_id', $userId)->whereNull('until')->count() > 0) {
            return true;
        }

        return false;
    }

    /**
     * Get route for the action
     */
    public function redirect(string $action, Timer $timer = null): RedirectResponse 
    {   
        switch ($action) {
            case 'project_timesheets':
                return redirect()->route('projects.timesheets', ['project' => $timer->project]);
                break;
            default:
                return redirect()->back();
        }
    }
}