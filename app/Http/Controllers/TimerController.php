<?php

namespace App\Http\Controllers;

use App\Models\Timer;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class TimerController extends Controller
{  
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        ]);

        if ($validator->fails()) {
            return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
        }

        $timer = new Timer();
        $timer->project_id = $request->project_id;
        $timer->user_id = Auth::id();
        $timer->since = $request->since;
        $timer->until = $request->until;
        $timer->save();

        Session::flash('message', 'Timer was created!');
        Session::flash('type', 'info');

        return redirect()->route('projects.timesheets', ['project' => $timer->project]);
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
        ]);

        if ($validator->fails()) {
            return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
        }

        Timer::where('id', $timer->id)
                    ->update([
                        'since' => $request->since,
                        'until' => $request->until,
                    ]);

        Session::flash('message', 'Timer was updated!');
        Session::flash('type', 'info');

        return redirect()->route('projects.timesheets', ['project' => $timer->project]);
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

        if(Timer::where('project_id', $request->project_id)->where('user_id', Auth::id())->whereNull('until')->count() > 0) {
            Session::flash('message', 'Another timer already running!');
            Session::flash('type', 'danger');

            return redirect()->back();
        }

        $timer = new Timer();
        $timer->project_id = $request->project_id;
        $timer->rate_id = $request->rate_id;
        $timer->user_id = Auth::id();
        $timer->since = Carbon::now();
        $timer->until = null;
        $timer->save();

        Session::flash('message', 'Timer started succesfully!');
        Session::flash('type', 'info');

        return redirect()->back();
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
        Timer::where('id', $timer->id)
                    ->update([
                        'until' => Carbon::now(),
                    ]);

        Session::flash('message', 'Timer stopped succesfully!');
        Session::flash('type', 'info');

        return redirect()->back();
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
