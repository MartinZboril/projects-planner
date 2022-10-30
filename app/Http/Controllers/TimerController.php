<?php

namespace App\Http\Controllers;

use App\Http\Requests\Timer\{StartTimerRequest, StoreTimerRequest, UpdateTimerRequest};
use App\Models\Timer;
use App\Models\Project;
use App\Services\TimerService;
use Illuminate\Support\Facades\Auth;

class TimerController extends Controller
{  
    protected $timerService;

    public function __construct(TimerService $timerService)
    {
        $this->middleware('auth');
        $this->timerService = $timerService;
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
        $fields = $request->validated();

        $timer = $this->timerService->store($fields);
        $this->timerService->flash('create');

        return $this->timerService->redirect('project_timesheets', $timer); 
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
        $fields = $request->validated();

        $timer = $this->timerService->update($timer, $fields);
        $this->timerService->flash('update');

        return $this->timerService->redirect('project_timesheets', $timer); 
    }

    /**
     * Start working on timer.
     */
    public function start(StartTimerRequest $request)
    {
        $fields = $request->validated();

        if($this->timerService->checkIfNotRunningAnoutherTimer($fields['project_id'], Auth::id())) {
            $this->timerService->flash('collision');            
            return $this->timerService->redirect(''); 
        }

        $timer = $this->timerService->start($fields);
        $this->timerService->flash('start');

        return $this->timerService->redirect('', $timer); 
    }

    /**
     * Stop working on timer.
     */
    public function stop(Timer $timer)
    {
        $timer = $this->timerService->stop($timer);
        $this->timerService->flash('stop');

        return $this->timerService->redirect('', $timer);
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
