<?php

namespace App\Http\Controllers\Project\Milestone;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\StoreCommentRequest;
use App\Http\Requests\Comment\UpdateCommentRequest;
use App\Models\Comment;
use App\Models\Milestone;
use App\Models\Project;
use App\Services\Data\CommentService;
use App\Services\Data\MilestoneService;
use App\Traits\FlashTrait;
use Exception;
use Illuminate\Support\Facades\Log;

class ProjectMilestoneCommentController extends Controller
{
    use FlashTrait;

    public function __construct(
        private MilestoneService $milestoneService,
        private CommentService $commentService
    ) {
    }

    /**
     * Display the comments of project.
     */
    public function index(Project $project, Milestone $milestone)
    {
        return view('projects.comments.index', ['milestone' => $milestone]);
    }

    /**
     * Store a newly created projects comment in storage.
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
     * Update the specified projects comment in storage.
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
}
