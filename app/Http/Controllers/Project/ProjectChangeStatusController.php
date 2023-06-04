<?php

namespace App\Http\Controllers\Project;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\Project\ChangeProjectRequest;
use App\Models\Project;
use App\Services\Data\ProjectService;

class ProjectChangeStatusController extends Controller
{
    public function __construct(
        private ProjectService $projectService
    ) {}

    /**
     * Change working status of the project.
     */
    public function __invoke(ChangeProjectRequest $request, Project $project): JsonResponse
    {
        try {
            $project = $this->projectService->handleChange($project, $request->status);
        } catch (Exception $exception) {
            Log::error($exception);
        }
        return response()->json([
            'message' => __('messages.project.' . $project->status->name),
            'project' => $project,
        ]);
    }
}
