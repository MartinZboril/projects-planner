<?php

namespace App\Http\Controllers\Project\Task;

use Exception;
use App\Models\Task;
use App\Models\Comment;
use App\Models\Project;
use App\Traits\FlashTrait;
use Illuminate\Http\JsonResponse;
use App\Services\Data\TaskService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\Data\CommentService;
use App\Http\Requests\Comment\StoreCommentRequest;
use App\Http\Requests\Comment\UpdateCommentRequest;

class ProjectTaskCommentController extends Controller
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
    public function store(StoreCommentRequest $request, Project $project, Task $task)
    {
        try {
            $this->commentService->handleSave(new Comment, $request->validated(), $task, $request->file('files'));
            $this->flash(__('messages.comment.create'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);

            return redirect()->back()->with(['error' => __('messages.error')]);
        }

        return redirect()->route('projects.tasks.show', ['project' => $project, 'task' => $task]);
    }

    /**
     * Update the specified tasks comment in storage.
     */
    public function update(UpdateCommentRequest $request, Project $project, Task $task, Comment $comment)
    {
        try {
            $comment = $this->commentService->handleSave($comment, $request->validated(), $task, $request->file('files'));
            $this->flash(__('messages.comment.update'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);

            return redirect()->back()->with(['error' => __('messages.error')]);
        }

        return redirect()->route('projects.tasks.show', ['project' => $project, 'task' => $task]);
    }
                
    /**
     * Remove the tasks comment from storage.
     */
    public function destroy(Project $project, Task $task, Comment $comment): JsonResponse
    {
        try {
            $this->commentService->handleDelete($comment);
        } catch (Exception $exception) {
            Log::error($exception);
        }

        return response()->json([
            'message' => __('messages.comment.delete'),
        ]);
    }    
}
