<?php

namespace App\Services\Data;

use App\Services\FileService;
use App\Models\{CommentFile, ClientComment, Comment, MilestoneComment, ProjectComment, TaskComment, TicketComment};
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ValidatedInput;

class CommentService
{
    /**
     * Save data for comment.
     */
    public function save(Comment $comment, ValidatedInput $inputs, ?Array $uploadedFiles): Comment
    {
        $comment = Comment::updateOrCreate(
            ['id' => $comment->id],
            [
                'user_id' => $comment->user_id ?? Auth::id(),
                'content' => $inputs->content,
            ]
        );

        if ($uploadedFiles) {
            $this->storeFiles($comment, $uploadedFiles);
        }

        return $comment;
    }

    /**
     * Save relation for comment.
     */
    protected function saveRelation(Comment $comment, int $parentId, string $parentType): void
    {
        switch ($parentType) {
            case 'project':
                ProjectComment::create(['project_id' => $parentId, 'comment_id' => $comment->id]);
                break;
            case 'milestone':
                MilestoneComment::create(['milestone_id' => $parentId, 'comment_id' => $comment->id]);
                break;
            case 'task':
                TaskComment::create(['task_id' => $parentId, 'comment_id' => $comment->id]);
                break;
            case 'ticket':
                TicketComment::create(['ticket_id' => $parentId, 'comment_id' => $comment->id]);
                break;
            default:
                throw new Exception('For the sent type was not found relationship to save!');
                break;
        }
    }

    /**
     * Store comments files.
     */
    protected function storeFiles(Comment $comment, Array $uploadedFiles): void
    {
        foreach ($uploadedFiles as $uploadedFile) {
            $fileId = ((new FileService)->upload($uploadedFile, 'comments'))->id;
            CommentFile::create(['comment_id' => $comment->id, 'file_id' => $fileId]);
        }
    }
}