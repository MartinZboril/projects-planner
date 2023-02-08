<?php

namespace App\Http\Controllers\Project;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\Project\ChangeProjectRequest;
use App\Models\Project;
use App\Traits\FlashTrait;
use App\Services\Data\ProjectService;

class ProjectChangeStatusController extends Controller
{
    use FlashTrait;

    public function __construct(private ProjectService $projectService)
    {
    }

    /**
     * Change working status of the project.
     */
    public function __invoke(ChangeProjectRequest $request, Project $project): RedirectResponse
    {
        try {
            $this->projectService->handleChange($project, $request->status);
            $this->flash(__('messages.project.' . $project->status->name), 'info');
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
        return redirect()->route('projects.show', $project);
    }
}
