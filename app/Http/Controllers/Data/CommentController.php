<?php

namespace App\Http\Controllers\Data;

use App\Models\Comment;
use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\{StoreCommentRequest, UpdateCommentRequest};
use App\Services\FlashService;
use App\Services\Data\CommentService;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    protected $commentService;
    protected $flashService;

    public function __construct(CommentService $commentService, FlashService $flashService)
    {
        $this->middleware('auth');
        $this->commentService = $commentService;
        $this->flashService = $flashService;
    }

    /**
     * Store a newly created comment in storage.
     */
    public function store(StoreCommentRequest $request)
    {
        try {
            $comment = $this->commentService->store($request->safe());
            $this->flashService->flash(__('messages.comment.create'), 'info');

            return $this->commentService->setUpRedirect($comment, $request->type, $request->parent_id);
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
    }

    /**
     * Update the specified comment in storage.
     */
    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        try {
            $comment = $this->commentService->update($comment, $request->safe());
            $this->flashService->flash(__('messages.comment.update'), 'info');

            return $this->commentService->setUpRedirect($comment, $request->type, $request->parent_id);
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
    }
}
