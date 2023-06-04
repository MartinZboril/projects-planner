<?php

namespace App\Http\Controllers\Task;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\File\UploadFileRequest;
use App\Models\Task;
use App\Traits\FlashTrait;
use App\Services\Data\TaskService;

class TaskFileUploaderController extends Controller
{
    use FlashTrait;

    public function __construct(
        private TaskService $taskService
    ) {}

    /**
     * Upload a newly created file in storage.
     */
    public function __invoke(UploadFileRequest $request, Task $task): RedirectResponse
    {
        try {
            $this->taskService->handleUploadFiles($task, $request->file('files'));
            $this->flash(__('messages.file.upload'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
        return redirect()->route('tasks.show', $task);
    }
}
