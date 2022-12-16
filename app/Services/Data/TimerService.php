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
     * Store new timer.
     */
    public function store(ValidatedInput $inputs): Timer
    {
        $timer = new Timer;
        $timer->project_id = $inputs->project_id;
        $timer->user_id = Auth::id();

        return $this->save($timer, $inputs);
    }

    /**
     * Update timer.
     */
    public function update(Timer $timer, ValidatedInput $inputs): Timer
    {
        return $this->save($timer, $inputs);
    }

    /**
     * Save data for rate.
     */
    protected function save(Timer $timer, ValidatedInput $inputs)
    {
        $timer->rate_id = $inputs->rate_id;
        $timer->since = $inputs->since;
        $timer->until = $inputs->until;
        $timer->note = $inputs->note;
        $timer->save();

        return $timer;
    }

    /**
     * Start measure new timer.
     */
    public function start(ValidatedInput $inputs): Timer
    {
        $timer = new Timer;
        $timer->project_id = $inputs->project_id;
        $timer->rate_id = $inputs->rate_id;
        $timer->user_id = Auth::id();
        $timer->since = now();
        $timer->until = null;
        $timer->save();

        return $timer;
    }

    /**
     * Stop measure the timer.
     */
    public function stop(Timer $timer): Timer
    {
        $timer->until = now();
        $timer->save();

        return $timer;
    }

    /**
     * Check if not running another timer of user in project.
     */
    public function checkIfNotRunningAnoutherTimer(int $projectId, int $userId): bool
    {
        if(Timer::where('project_id', $projectId)->where('user_id', $userId)->whereNull('until')->count() > 0) {
            return true;
        }

        return false;
    }

    /**
     * Set up redirect for the action
     */
    public function setUpRedirect(Timer $timer): RedirectResponse
    {
        return (new RouteService)->redirect(ProjectRouteEnum::Timesheets->value, ['project' => $timer->project]);
    }
}