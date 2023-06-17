<?php

namespace App\Http\Controllers\Project\Task;

use App\Http\Controllers\Controller;
use App\Http\Requests\File\UploadFileRequest;
use App\Models\Project;
use App\Models\Task;
use App\Services\Data\TaskService;
use App\Traits\FlashTrait;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class ProjectTaskFileUploaderController extends Controller
{
    use FlashTrait;

    public function __construct(
        private TaskService $taskService
    ) {
    }

    /**
     * Upload a newly created file in storage.
     */
    public function __invoke(UploadFileRequest $request, Project $project, Task $task): RedirectResponse
    {
        try {
            $this->taskService->handleUploadFiles($task, $request->file('files'));
            $this->flash(__('messages.file.upload'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);

            return redirect()->back()->with(['error' => __('messages.error')]);
        }

        return redirect()->route('projects.tasks.show', ['project' => $project, 'task' => $task]);
    }
}
