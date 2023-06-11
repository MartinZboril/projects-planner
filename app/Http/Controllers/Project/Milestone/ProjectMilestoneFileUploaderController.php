<?php

namespace App\Http\Controllers\Project\Milestone;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\File\UploadFileRequest;
use App\Models\{Project, Milestone};
use App\Traits\FlashTrait;
use App\Services\Data\MilestoneService;

class ProjectMilestoneFileUploaderController extends Controller
{
    use FlashTrait;

    public function __construct(
        private MilestoneService $milestoneService
    ) {}

    /**
     * Upload a newly created file in storage.
     */
    public function __invoke(UploadFileRequest $request, Project $project, Milestone $milestone): RedirectResponse
    {
        try {
            $this->milestoneService->handleUploadFiles($milestone, $request->file('files'));
            $this->flash(__('messages.file.upload'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
        return redirect()->route('projects.milestones.show', ['project' => $project, 'milestone' => $milestone]);
    }
}

