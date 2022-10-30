<?php

namespace App\Http\Controllers;

use App\Http\Requests\Milestone\{LoadMilestoneRequest, StoreMilestoneRequest, UpdateMilestoneRequest};
use App\Models\{Milestone, Project};
use App\Services\MilestoneService;

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
     */
    public function store(StoreMilestoneRequest $request)
    {
        $fields = $request->validated();
        $milestone = $this->milestoneService->store($fields);
        $this->milestoneService->flash('create');

        $redirectAction = isset($fields['create_and_close']) ? 'project_milestones' : 'milestone';
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
     */
    public function update(UpdateMilestoneRequest $request, Milestone $milestone)
    {
        $fields = $request->validated();
        $milestone = $this->milestoneService->update($milestone, $fields);
        $this->milestoneService->flash('update');

        $redirectAction = isset($fields['save_and_close']) ? 'project_milestones' : 'milestone';
        return $this->milestoneService->redirect($redirectAction, $milestone);  
    }

    public function load(LoadMilestoneRequest $request)
    {
        $fields = $request->validated();
        return Milestone::where('project_id', $fields['project_id'])->select('id', 'name')->get();
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
