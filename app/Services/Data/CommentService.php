<?php

namespace App\Services\Data;

use Illuminate\Support\Facades\Auth;
use App\Models\Comment;
use App\Services\FileService;

class CommentService
{
    /**
     * Save data for comment.
     */
    public function handleSave(Comment $comment, array $inputs, ?Array $uploadedFiles): Comment
    {
        // Prepare fields
        $inputs['user_id'] = $comment->user_id ?? Auth::id();
        // Save note
        $comment->fill($inputs)->save();
        // Store comments files
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