<?php

namespace App\Http\Controllers\Data;

use Exception;
use App\Models\Comment;
use App\Services\FlashService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\Data\CommentService;
use App\Http\Requests\Comment\{StoreCommentRequest, UpdateCommentRequest};

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
            $this->commentService->save(new Comment, $request->safe(), $request->file('files'));
            $this->flashService->flash(__('messages.comment.create'), 'info');
            return redirect()->back();
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
            $this->commentService->save($comment, $request->safe(), $request->file('files'));
            $this->flashService->flash(__('messages.comment.update'), 'info');
            return redirect()->back();
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
    }
}
