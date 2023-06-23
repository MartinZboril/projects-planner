<?php

namespace App\Http\Controllers\Project\Milestone;

use Exception;
use App\Models\Comment;
use App\Models\Project;
use App\Models\Milestone;
use App\Traits\FlashTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\Data\CommentService;
use App\Services\Data\MilestoneService;
use App\Http\Requests\Comment\StoreCommentRequest;
use App\Http\Requests\Comment\UpdateCommentRequest;

class ProjectMilestoneCommentController extends Controller
{
    use FlashTrait;

    public function __construct(
        private MilestoneService $milestoneService,
        private CommentService $commentService
    ) {
    }

    /**
     * Store a newly created milestones comment in storage.
     */
    public function store(StoreCommentRequest $request, Project $project, Milestone $milestone)
    {
        try {
            $this->commentService->handleSave(new Comment, $request->validated(), $milestone, $request->file('files'));
            $this->flash(__('messages.comment.create'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);

            return redirect()->back()->with(['error' => __('messages.error')]);
        }

        return redirect()->route('projects.milestones.show', ['project' => $project, 'milestone' => $milestone]);
    }

    /**
     * Update the specified milestones comment in storage.
     */
    public function update(UpdateCommentRequest $request, Project $project, Milestone $milestone, Comment $comment)
    {
        try {
            $this->commentService->handleSave($comment, $request->validated(), $milestone, $request->file('files'));
            $this->flash(__('messages.comment.update'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);

            return redirect()->back()->with(['error' => __('messages.error')]);
        }

        return redirect()->route('projects.milestones.show', ['project' => $project, 'milestone' => $milestone]);
    }
            
    /**
     * Remove the milestones comment from storage.
     */
    public function destroy(Project $project, Milestone $milestone, Comment $comment): JsonResponse
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
