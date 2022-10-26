<?php

namespace App\Http\Controllers;

use App\Models\Timer;
use App\Models\Project;
use App\Services\TimerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'since' => ['required', 'date_format:Y-m-d H:i'],
            'until' => ['required', 'date_format:Y-m-d H:i', 'after:since'],
            'rate_id' => ['required', 'integer', 'exists:rates,id'],
        ]);

        if ($validator->fails()) {
            return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
        }

        $timer = $this->timerService->store($request);
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
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Timer  $timer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Timer $timer)
    {
        $validator = Validator::make($request->all(), [
            'since' => ['required', 'date_format:Y-m-d H:i'],
            'until' => ['required', 'date_format:Y-m-d H:i', 'after:since'],
            'rate_id' => ['required', 'integer', 'exists:rates,id'],
        ]);

        if ($validator->fails()) {
            return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
        }

        $timer = $this->timerService->update($timer, $request);
        $this->timerService->flash('update');

        return $this->timerService->redirect('project_timesheets', $timer); 
    }

    /**
     * Start working on timer.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function start(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => ['required', 'integer', 'exists:projects,id'],
            'rate_id' => ['required', 'integer', 'exists:rates,id'],
        ]);

        if ($validator->fails()) {
            return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
        }

        if($this->timerService->checkIfNotRunningAnoutherTimer($request->project_id, Auth::id())) {
            $this->timerService->flash('collision');            
            return $this->timerService->redirect(''); 
        }

        $timer = $this->timerService->start($request);
        $this->timerService->flash('start');

        return $this->timerService->redirect('', $timer); 
    }

    /**
     * Stop working on timer.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Timer  $timer
     * @return \Illuminate\Http\Response
     */
    public function stop(Request $request, Timer $timer)
    {
        $timer = $this->timerService->stop($timer, $request);
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
