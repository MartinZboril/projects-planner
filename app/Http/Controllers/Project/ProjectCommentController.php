<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\StoreCommentRequest;
use App\Http\Requests\Comment\UpdateCommentRequest;
use App\Models\Comment;
use App\Models\Project;
use App\Services\Data\CommentService;
use App\Services\Data\ProjectService;
use App\Traits\FlashTrait;
use Exception;
use Illuminate\Support\Facades\Log;

class ProjectCommentController extends Controller
{
    use FlashTrait;

    public function __construct(
        private ProjectService $projectService,
        private CommentService $commentService
    ) {
    }

    /**
     * Display the comments of project.
     */
    public function index(Project $project)
    {
        return view('projects.comments.index', ['project' => $project]);
    }

    /**
     * Store a newly created projects comment in storage.
     */
    public function store(StoreCommentRequest $request, Project $project)
    {
        try {
            $this->commentService->handleSave(new Comment, $request->validated(), $project, $request->file('files'));
            $this->flash(__('messages.comment.create'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);

            return redirect()->back()->with(['error' => __('messages.error')]);
        }

        return redirect()->route('projects.comments.index', $project);
    }

    /**
     * Update the specified projects comment in storage.
     */
    public function update(UpdateCommentRequest $request, Project $project, Comment $comment)
    {
        try {
            $this->commentService->handleSave($comment, $request->validated(), $project, $request->file('files'));
            $this->flash(__('messages.comment.update'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);

            return redirect()->back()->with(['error' => __('messages.error')]);
        }

        return redirect()->route('projects.comments.index', $project);
    }
}
