<?php

namespace App\Services\Data;

use App\Services\FileService;
use App\Models\{CommentFile, Comment};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ValidatedInput;

class CommentService
{
    /**
     * Save data for comment.
     */
    public function handleSave(Comment $comment, ValidatedInput $inputs, ?Array $uploadedFiles): Comment
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
     * Store comments files.
     */
    private function storeFiles(Comment $comment, Array $uploadedFiles): void
    {
        foreach ($uploadedFiles as $uploadedFile) {
            $fileId = ((new FileService)->handleUpload($uploadedFile, 'comments'))->id;
            CommentFile::create(['comment_id' => $comment->id, 'file_id' => $fileId]);
        }
    }
}