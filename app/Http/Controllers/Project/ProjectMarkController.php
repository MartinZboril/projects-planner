<?php

namespace App\Http\Controllers\Project;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Services\Data\ProjectService;

class ProjectMarkController extends Controller
{
    public function __construct(
        private ProjectService $projectService
    ) {}

    /**
     * Mark selected project.
     */
    public function __invoke(Project $project): JsonResponse
    {
        try {
            $project = $this->projectService->handleMark($project);
        } catch (Exception $exception) {
            Log::error($exception);
        }
        return response()->json([
            'message' => __('messages.project.' . ($project->is_marked ? 'mark' : 'unmark')),
            'project' => $project,
        ]);    
    }   
}
