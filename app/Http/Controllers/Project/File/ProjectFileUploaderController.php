<?php

namespace App\Http\Controllers\Project\File;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use App\Models\Project;
use App\Http\Requests\File\UploadFileRequest;
use App\Http\Controllers\Controller;
use App\Traits\FlashTrait;
use App\Services\Data\ProjectService;

class ProjectFileUploaderController extends Controller
{
    use FlashTrait;

    public function __construct(private ProjectService $projectService)
    {
    }

    /**
     * Upload a newly created file in storage.
     */
    public function __invoke(UploadFileRequest $request, Project $project): RedirectResponse
    {
        try {
            $this->projectService->handleUploadFiles($project, $request->file('files'));
            $this->flash(__('messages.file.upload'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
        return redirect()->route('projects.files.index', $project);
    }
}
