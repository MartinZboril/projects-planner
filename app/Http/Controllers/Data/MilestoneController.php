<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Http\Requests\Milestone\{LoadMilestoneRequest, StoreMilestoneRequest, UpdateMilestoneRequest};
use App\Models\{Milestone, Project};
use App\Services\FlashService;
use App\Services\Data\MilestoneService;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

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
     * Show the form for creating a new milestone.
     */
    public function create(Project $project): View
    {
        return view('milestones.create', ['project' => $project]);
    }

    /**
     * Store a newly created milestone in storage.
     */
    public function store(StoreMilestoneRequest $request): RedirectResponse
    {
        try {
            $fields = $request->validated();
            $milestone = $this->milestoneService->store($fields);
            $this->flashService->flash(__('messages.milestone.create'), 'info');

            $redirectAction = isset($request->create_and_close) ? 'project_milestones' : 'milestone';
            return $this->milestoneService->redirect($redirectAction, $milestone);
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        } 
    }

    /**
     * Display the milestone.
     */
    public function detail(Project $project, Milestone $milestone): View
    {
        return view('milestones.detail', ['project' => $project, 'milestone' => $milestone]);
    }

    /**
     * Show the form for editing the milestone.
     */
    public function edit(Project $project, Milestone $milestone): View
    {
        return view('milestones.edit', ['project' => $project, 'milestone' => $milestone]);
    }

    /**
     * Update the milestone in storage.
     */
    public function update(UpdateMilestoneRequest $request, Milestone $milestone): RedirectResponse
    {
        try {
            $fields = $request->validated();
            $milestone = $this->milestoneService->update($milestone, $fields);
            $this->flashService->flash(__('messages.milestone.update'), 'info');

            $redirectAction = isset($request->save_and_close) ? 'project_milestones' : 'milestone';
            return $this->milestoneService->redirect($redirectAction, $milestone); 
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        } 
    }

    /**
     * Load the milestones by project.
     */
    public function load(LoadMilestoneRequest $request): Milestone
    {
        $fields = $request->validated();
        return Milestone::where('project_id', $fields['project_id'])->get(['id', 'name']);
    }
}
