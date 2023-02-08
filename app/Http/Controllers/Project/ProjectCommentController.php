<?php

namespace App\Http\Controllers\Project;

use Exception;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\{StoreCommentRequest, UpdateCommentRequest};
use App\Models\{Comment, Project};
use App\Traits\FlashTrait;
use App\Services\Data\{ProjectService, CommentService};

class ProjectCommentController extends Controller
{
    use FlashTrait;

    public function __construct(private ProjectService $projectService, private CommentService $commentService)
    {
    }
    
    /**
     * Display the comments of project.
     */
    public function index(Project $project)
    {
        return view('projects.comments.index', ['project' => $project, 'comment' => new Comment]);
    }

    /**
     * Store a newly created projects comment in storage.
     */
    public function store(StoreCommentRequest $request, Project $project)
    {
        try {
            $this->projectService->handleSaveComment(
                $project,
                $this->commentService->handleSave(new Comment, $request->safe(), $request->file('files'))
            );
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
            $this->commentService->handleSave($comment, $request->safe(), $request->file('files'));
            $this->flash(__('messages.comment.update'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
        return redirect()->route('projects.comments.index', $project);
    }
}
