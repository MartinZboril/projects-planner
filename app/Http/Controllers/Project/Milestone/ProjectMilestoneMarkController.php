<?php

namespace App\Http\Controllers\Project\Milestone;

use App\Http\Controllers\Controller;
use App\Models\Milestone;
use App\Models\Project;
use App\Services\Data\MilestoneService;
use App\Traits\FlashTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ProjectMilestoneMarkController extends Controller
{
    use FlashTrait;

    public function __construct(
        private MilestoneService $milestoneService
    ) {
    }

    /**
     * Mark selected milestone.
     */
    public function __invoke(Project $project, Milestone $milestone): JsonResponse
    {
        try {
            $milestone = $this->milestoneService->handleMark($milestone);
        } catch (Exception $exception) {
            Log::error($exception);
        }

        return response()->json([
            'message' => __('messages.milestone.'.($milestone->is_marked ? 'mark' : 'unmark')),
            'project' => $project,
            'milestone' => $milestone,
        ]);
    }
}
