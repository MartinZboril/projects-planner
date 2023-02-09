<?php

namespace App\Http\Controllers\Project;

use Exception;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\{StoreCommentRequest, UpdateCommentRequest};
use App\Models\{Comment, Project, Task};
use App\Traits\FlashTrait;
use App\Services\Data\{CommentService, TaskService};

class ProjectTaskCommentController extends Controller
{
    use FlashTrait;

    public function __construct(private TaskService $taskService, private CommentService $commentService)
    {
    }

    /**
     * Store a newly created tasks comment in storage.
     */
    public function store(StoreCommentRequest $request, Project $project, Task $task)
    {
        try {
            $this->taskService->handleSaveComment(
                $task,
                $this->commentService->handleSave(new Comment, $request->safe(), $request->file('files'))
            );
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
            $comment = $this->commentService->handleSave($comment, $request->safe(), $request->file('files'));
            $this->flash(__('messages.comment.update'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
        return redirect()->route('projects.tasks.show', ['project' => $project, 'task' => $task]);
    }
}