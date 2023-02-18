<?php

namespace App\Http\Controllers\Project;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Services\Data\ProjectService;
use App\Traits\FlashTrait;

class ProjectMarkController extends Controller
{
    use FlashTrait;

    public function __construct(private ProjectService $projectService)
    {
    }
    /**
     * Mark selected project.
     */
    public function __invoke(Project $project): RedirectResponse
    {
        try {
            $project = $this->projectService->handleMark($project);
            $this->flash(__('messages.project.' . ($project->is_marked ? 'mark' : 'unmark')), 'info');
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
        return redirect()->route('projects.show', $project);
    }   
}
