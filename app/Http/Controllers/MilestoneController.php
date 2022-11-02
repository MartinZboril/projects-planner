<?php

namespace App\Http\Controllers;

use App\Http\Requests\Milestone\{LoadMilestoneRequest, StoreMilestoneRequest, UpdateMilestoneRequest};
use App\Models\{Milestone, Project};
use App\Services\FlashService;
use App\Services\Data\MilestoneService;
use Exception;
use Illuminate\Support\Facades\Log;

class MilestoneController extends Controller
{   
    protected $milestoneService;
    protected $flashService;

    public function __construct(MilestoneService $milestoneService, FlashService $flashService)
    {
        $this->middleware('auth');
        $this->milestoneService = $milestoneService;
        $this->flashService = $flashService;
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
        try {
            $fields = $request->validated();
            $milestone = $this->milestoneService->store($fields);
            $this->flashService->flash(__('messages.milestone.create'), 'info');

            $redirectAction = isset($fields['create_and_close']) ? 'project_milestones' : 'milestone';
            return $this->milestoneService->redirect($redirectAction, $milestone);
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        } 
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
        try {
            $fields = $request->validated();
            $milestone = $this->milestoneService->update($milestone, $fields);
            $this->flashService->flash(__('messages.milestone.update'), 'info');

            $redirectAction = isset($fields['save_and_close']) ? 'project_milestones' : 'milestone';
            return $this->milestoneService->redirect($redirectAction, $milestone); 
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        } 
    }

    public function load(LoadMilestoneRequest $request)
    {
        try {
            $fields = $request->validated();
            return Milestone::where('project_id', $fields['project_id'])->get(['id', 'name']);
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
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
