<?php

namespace App\Http\Controllers;

use App\Models\Milestone;
use App\Models\Project;
use App\Services\MilestoneService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MilestoneController extends Controller
{   
    protected $milestoneService;

    public function __construct(MilestoneService $milestoneService)
    {
        $this->middleware('auth');
        $this->milestoneService = $milestoneService;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Project $project)
    {
        return view('milestones.create', ['project' => $project]);
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
            'name' => ['required', 'string', 'max:255'],
            'owner_id' => ['required', 'integer', 'exists:users,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'colour' => ['required', 'string', 'max:255'],
            'description' => ['required'],
        ]);

        if ($validator->fails()) {
            return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
        }

        $milestone = $this->milestoneService->store($request);
        $this->milestoneService->flash('create');

        $redirectAction = $request->create_and_close ? 'project_milestones' : 'milestone';
        return $this->milestoneService->redirect($redirectAction, $milestone); 

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Milestone  $milestone
     * @return \Illuminate\Http\Response
     */
    public function detail(Project $project, Milestone $milestone)
    {
        return view('milestones.detail', ['project' => $project, 'milestone' => $milestone]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Milestone  $milestone
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project, Milestone $milestone)
    {
        return view('milestones.edit', ['project' => $project, 'milestone' => $milestone]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Milestone  $milestone
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Milestone $milestone)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'owner_id' => ['required', 'integer', 'exists:users,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'colour' => ['required', 'string', 'max:255'],
            'description' => ['required'],
        ]);

        if ($validator->fails()) {
            return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
        }

        $milestone = $this->milestoneService->update($milestone, $request);
        $this->milestoneService->flash('update');

        $redirectAction = $request->save_and_close ? 'project_milestones' : 'milestone';
        return $this->milestoneService->redirect($redirectAction, $milestone);  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Milestone  $milestone
     * @return \Illuminate\Http\Response
     */
    public function destroy(Milestone $milestone)
    {
        //
    }
}
