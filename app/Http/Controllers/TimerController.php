<?php

namespace App\Http\Controllers;

use App\Http\Requests\Timer\{StartTimerRequest, StoreTimerRequest, UpdateTimerRequest};
use App\Models\{Project, Timer};
use App\Services\FlashService;
use App\Services\Data\TimerService;
use Exception;
use Illuminate\Support\Facades\{Auth, Log};

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
     * Show the form for creating a new resource.
     * 
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function create(Project $project)
    {
        return view('timers.create', ['project' => $project]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTimerRequest $request)
    {
        try {
            $fields = $request->validated();
            $timer = $this->timerService->store($fields);
            $this->flashService->flash(__('messages.timer.create'), 'info');

            return $this->timerService->redirect('project_timesheets', $timer); 
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Timer  $timer
     * @return \Illuminate\Http\Response
     */
    public function show(Timer $timer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @param  \App\Models\Timer  $timer
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project, Timer $timer)
    {
        return view('timers.edit', ['project' => $project, 'timer' => $timer]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTimerRequest $request, Timer $timer)
    {
        try {
            $fields = $request->validated();
            $timer = $this->timerService->update($timer, $fields);
            $this->flashService->flash(__('messages.timer.create'), 'info');

            return $this->timerService->redirect('project_timesheets', $timer); 
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
    }

    /**
     * Start working on timer.
     */
    public function start(StartTimerRequest $request)
    {
        try {
            $fields = $request->validated();

            if($this->timerService->checkIfNotRunningAnoutherTimer($fields['project_id'], Auth::id())) {
                $this->flashService->flash(__('messages.timer.collision'), 'info');
                return $this->timerService->redirect(''); 
            }

            $timer = $this->timerService->start($fields);
            $this->flashService->flash(__('messages.timer.start'), 'info');

            return $this->timerService->redirect('', $timer); 
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
    }

    /**
     * Stop working on timer.
     */
    public function stop(Timer $timer)
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Timer  $timer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Timer $timer)
    {
        //
    }
}
