<?php

namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\StoreCommentRequest;
use App\Http\Requests\Comment\UpdateCommentRequest;
use App\Models\Comment;
use App\Models\Task;
use App\Services\Data\CommentService;
use App\Services\Data\TaskService;
use App\Traits\FlashTrait;
use Exception;
use Illuminate\Support\Facades\Log;

class TaskCommentController extends Controller
{
    use FlashTrait;

    public function __construct(
        private TaskService $taskService,
        private CommentService $commentService
    ) {
    }

    /**
     * Store a newly created tasks comment in storage.
     */
    public function store(StoreCommentRequest $request, Task $task)
    {
        try {
            $this->commentService->handleSave(new Comment, $request->validated(), $task, $request->file('files'));
            $this->flash(__('messages.comment.create'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);

            return redirect()->back()->with(['error' => __('messages.error')]);
        }

        return redirect()->route('tasks.show', $task);
    }

    /**
     * Update the specified tasks comment in storage.
     */
    public function update(UpdateCommentRequest $request, Task $task, Comment $comment)
    {
        try {
            $comment = $this->commentService->handleSave($comment, $request->validated(), $task, $request->file('files'));
            $this->flash(__('messages.comment.update'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);

            return redirect()->back()->with(['error' => __('messages.error')]);
        }

        return redirect()->route('tasks.show', $task);
    }
}
