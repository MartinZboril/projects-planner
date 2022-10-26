<?php

namespace App\Services;

use App\Models\Timer;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class TimerService
{
    public function store(Request $request): Timer
    {
        $timer = new Timer;
        $timer->project_id = $request->project_id;
        $timer->user_id = Auth::id();
        $timer->since = $request->since;
        $timer->until = $request->until;
        $timer->save();

        return $timer;
    }

    public function update(Timer $timer, Request $request): Timer
    {
        Timer::where('id', $timer->id)
                    ->update([
                        'since' => $request->since,
                        'until' => $request->until,
                    ]);

        return $timer;
    }

    public function start(Request $request): Timer
    {
        $timer = new Timer;
        $timer->project_id = $request->project_id;
        $timer->rate_id = $request->rate_id;
        $timer->user_id = Auth::id();
        $timer->since = Carbon::now();
        $timer->until = null;
        $timer->save();

        return $timer;
    }

    public function stop(Timer $timer, Request $request): Timer
    {
        Timer::where('id', $timer->id)
                    ->update([
                        'until' => Carbon::now(),
                    ]);

        return $timer;
    }

    public function checkIfNotRunningAnoutherTimer(int $projectId, int $userId): bool
    {
        if(Timer::where('project_id', $projectId)->where('user_id', $userId)->whereNull('until')->count() > 0) {
            return true;
        }

        return false;
    }

    public function flash(string $action): void
    {
        switch ($action) {
            case 'create':
                Session::flash('message', __('messages.timer.create'));
                Session::flash('type', 'info');
                break;
            case 'update':
                Session::flash('message', __('messages.timer.update'));
                Session::flash('type', 'info');
                break;
            case 'start':
                Session::flash('message', __('messages.timer.start'));
                Session::flash('type', 'info');
                break;
            case 'stop':
                Session::flash('message', __('messages.timer.stop'));
                Session::flash('type', 'info');
                break;
            case 'collision':
                Session::flash('message', __('messages.timer.collision'));
                Session::flash('type', 'danger');
                break;
            default:
                Session::flash('message', __('messages.complete'));
                Session::flash('type', 'info');
        }
    }

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