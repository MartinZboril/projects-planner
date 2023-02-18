<?php

namespace App\Services\Data;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ValidatedInput;
use App\Models\Comment;
use App\Services\FileService;

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
            $comment->files()->save((new FileService)->handleUpload($uploadedFile, 'comments'));
        }
    }
}