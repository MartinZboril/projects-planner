<?php

namespace App\Services\Data;

use App\Models\CommentFile;
use App\Models\TaskComment;
use App\Models\TicketComment;
use App\Services\FileService;
use App\Models\MilestoneComment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\ValidatedInput;
use App\Models\{ClientComment, Comment, ProjectComment};

class CommentService
{
    /**
     * Store new comment.
     */
    public function store(ValidatedInput $inputs, ?Array $uploadedFiles): Comment
    {
        $comment = new Comment;
        $comment->user_id = Auth::id();

        $comment = $this->save($comment, $inputs);

        if ($parentId = $inputs->parent_id) {
            $this->saveRelation($comment, $parentId, $inputs->type);
        }

        if ($uploadedFiles) {
            $this->storeFiles($comment, $uploadedFiles);
        }

        return $comment;
    }

    /**
     * Update comment.
     */
    public function update(Comment $comment, ValidatedInput $inputs, ?Array $uploadedFiles): Comment
    {
        $comment = $this->save($comment, $inputs);

        if ($uploadedFiles) {
            $this->storeFiles($comment, $uploadedFiles);
        }

        return $comment;
    }

    /**
     * Save data for comment.
     */
    protected function save(Comment $comment, ValidatedInput $inputs): Comment
    {
        $comment->content = $inputs->content;
        $comment->save();

        return $comment;
    }

    protected function saveRelation(Comment $comment, int $parentId, string $type): void
    {
        if ($type == 'client') {
            ClientComment::create([
                'client_id' => $parentId,
                'comment_id' => $comment->id
            ]);
        } elseif ($type == 'project') {
            ProjectComment::create([
                'project_id' => $parentId,
                'comment_id' => $comment->id
            ]);
        } elseif ($type == 'milestone') {
            MilestoneComment::create([
                'milestone_id' => $parentId,
                'comment_id' => $comment->id
            ]);
        } elseif ($type == 'task') {
            TaskComment::create([
                'task_id' => $parentId,
                'comment_id' => $comment->id
            ]);
        } elseif ($type == 'ticket') {
            TicketComment::create([
                'ticket_id' => $parentId,
                'comment_id' => $comment->id
            ]);
        }
    }

    /**
     * Store comments files.
     */
    public function storeFiles(Comment $comment, Array $uploadedFiles): void
    {
        foreach ($uploadedFiles as $uploadedFile) {
            CommentFile::create([
                'comment_id' => $comment->id,
                'file_id' => ((new FileService)->upload($uploadedFile, 'comments'))->id
            ]);
        }
    }

    /**
     * Set up redirect for the action
     */
    public function setUpRedirect(Comment $comment, $type, $parentId): RedirectResponse
    {
        return redirect()->back();
    }
}