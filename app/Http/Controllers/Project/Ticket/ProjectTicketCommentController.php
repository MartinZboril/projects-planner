<?php

namespace App\Http\Controllers\Project\Ticket;

use Exception;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\{StoreCommentRequest, UpdateCommentRequest};
use App\Models\{Comment, Project, Ticket};
use App\Traits\FlashTrait;
use App\Services\Data\{CommentService, TicketService};

class ProjectTicketCommentController extends Controller
{
    use FlashTrait;

    public function __construct(private TicketService $ticketService, private CommentService $commentService)
    {
    }

    /**
     * Store a newly created tickets comment in storage.
     */
    public function store(StoreCommentRequest $request, Project $project, Ticket $ticket)
    {
        try {
            $this->commentService->handleSave(new Comment, $request->validated(), $ticket, $request->file('files'));
            $this->flash(__('messages.comment.create'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
        return redirect()->route('projects.tickets.show', ['project' => $project, 'ticket' => $ticket]);
    }

    /**
     * Update the specified tickets comment in storage.
     */
    public function update(UpdateCommentRequest $request, Project $project, Ticket $ticket, Comment $comment)
    {
        try {
            $comment = $this->commentService->handleSave($comment, $request->validated(), $ticket, $request->file('files'));
            $this->flash(__('messages.comment.update'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
        return redirect()->route('projects.tickets.show', ['project' => $project, 'ticket' => $ticket]);
    }
}
