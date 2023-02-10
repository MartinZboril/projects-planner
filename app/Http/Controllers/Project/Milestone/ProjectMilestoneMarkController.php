<?php

namespace App\Http\Controllers\Project\Milestone;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\{Project, Milestone};
use App\Traits\FlashTrait;
use App\Services\Data\MilestoneService;

class ProjectMilestoneMarkController extends Controller
{
    use FlashTrait;

    public function __construct(private MilestoneService $milestoneService)
    {
    }

    /**
     * Mark selected milestone.
     */
    public function __invoke(Project $project, Milestone $milestone): RedirectResponse
    {
        try {
            $milestone = $this->milestoneService->handleMark($milestone);
            $this->flash(__('messages.milestone.' . ($milestone->is_marked ? 'mark' : 'unmark')), 'info');
            return redirect()->back();
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
        return redirect()->route('projects.milestones.show', ['project' => $project, 'milestone' => $milestone]);
    } 
}
