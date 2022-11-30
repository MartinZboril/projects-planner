<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Http\Requests\Timer\{StartTimerRequest, StoreTimerRequest, UpdateTimerRequest};
use App\Models\{Project, Timer};
use App\Services\FlashService;
use App\Services\Data\TimerService;
use Exception;
use Illuminate\Support\Facades\{Auth, Log};
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TimerController extends Controller
{  
    protected $timerService;

    public function __construct(TimerService $timerService, FlashService $flashService)
    {
        $this->middleware('auth');
        $this->timerService = $timerService;
        $this->flashService = $flashService;
    }

    /**
     * Show the form for creating a new timer.
     */
    public function create(Project $project): View
    {
        return view('timers.create', ['project' => $project]);
    }

    /**
     * Store a newly created timer in storage.
     */
    public function store(StoreTimerRequest $request): RedirectResponse
    {
        try {
            $timer = $this->timerService->store($request->safe());
            $this->flashService->flash(__('messages.timer.create'), 'info');

            return $this->timerService->redirect('project_timesheets', $timer); 
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
    }

    /**
     * Show the form for editing the timer.
     */
    public function edit(Project $project, Timer $timer): View
    {
        return view('timers.edit', ['project' => $project, 'timer' => $timer]);
    }

    /**
     * Update the timer in storage.
     */
    public function update(UpdateTimerRequest $request, Timer $timer): RedirectResponse
    {
        try {
            $timer = $this->timerService->update($timer, $request->safe());
            $this->flashService->flash(__('messages.timer.create'), 'info');

            return $this->timerService->redirect('project_timesheets', $timer); 
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
    }

    /**
     * Start working on new timer.
     */
    public function start(StartTimerRequest $request): RedirectResponse
    {
        try {
            if($this->timerService->checkIfNotRunningAnoutherTimer($request->project_id, Auth::id())) {
                $this->flashService->flash(__('messages.timer.collision'), 'info');
                return $this->timerService->redirect(''); 
            }

            $timer = $this->timerService->start($request->safe());
            $this->flashService->flash(__('messages.timer.start'), 'info');

            return $this->timerService->redirect('', $timer); 
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
    }

    /**
     * Stop working on the timer.
     */
    public function stop(Timer $timer): RedirectResponse
    {
        try {
            $timer = $this->timerService->stop($timer);
            $this->flashService->flash(__('messages.timer.stop'), 'info');

            return $this->timerService->redirect('', $timer);
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
    }
}
