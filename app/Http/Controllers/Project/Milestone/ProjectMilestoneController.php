<?php

namespace App\Http\Controllers\Project\Milestone;

use Exception;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\{JsonResponse, RedirectResponse};
use App\DataTables\{MilestonesDataTable, TasksDataTable};
use App\Http\Controllers\Controller;
use App\Http\Requests\Milestone\{StoreMilestoneRequest, UpdateMilestoneRequest};
use App\Models\{Milestone, Project};
use App\Traits\FlashTrait;
use App\Services\Data\MilestoneService;

class ProjectMilestoneController extends Controller
{
    use FlashTrait;

    public function __construct(private MilestoneService $milestoneService)
    {
    }

    /**
     * Display the milestones of project.
     */
    public function index(Project $project, MilestonesDataTable $milestonesDataTable): JsonResponse|View
    {
        return $milestonesDataTable->with([
            'project_id' => $project->id,
            'view' => 'project',
        ])->render('projects.milestones.index', ['project' => $project]);
    }

    /**
     * Show the form for creating a new milestone.
     */
    public function create(Project $project): View
    {
        return view('projects.milestones.create', ['project' => $project]);
    }

    /**
     * Store a newly created milestone in storage.
     */
    public function store(StoreMilestoneRequest $request, Project $project): RedirectResponse
    {
        try {
            $milestone = $this->milestoneService->handleSave(new Milestone, $request->validated());
            $this->flash(__('messages.milestone.create'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
        return redirect()->route('projects.milestones.show', ['project' => $project, 'milestone' => $milestone]);
    }

    /**
     * Display the milestone.
     */
    public function show(Project $project, Milestone $milestone, TasksDataTable $tasksDataTable): JsonResponse|View
    {
        return $tasksDataTable->with([
            'milestone_id' => $milestone->id,
            'view' => 'milestone',
        ])->render('projects.milestones.show', ['milestone' => $milestone]);
    }

    /**
     * Show the form for editing the milestone.
     */
    public function edit(Project $project, Milestone $milestone): View
    {
        return view('projects.milestones.edit', ['milestone' => $milestone]);
    }

    /**
     * Update the milestone in storage.
     */
    public function update(UpdateMilestoneRequest $request, Project $project, Milestone $milestone): RedirectResponse
    {
        try {
            $milestone = $this->milestoneService->handleSave($milestone, $request->validated());
            $this->flash(__('messages.milestone.update'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        } 
        return redirect()->route('projects.milestones.show', ['project' => $project, 'milestone' => $milestone]);
    }
}
