<?php

namespace App\Services\Data;

use Illuminate\Support\Facades\Auth;
use App\Models\Comment;
use App\Services\FileService;
use Illuminate\Database\Eloquent\Model;

class CommentService
{
    public function __construct(
        private FileService $fileService,
    ) {}

    /**
     * Save data for comment.
     */
    public function handleSave(Comment $comment, array $inputs, Model $model, ?Array $uploadedFiles): Comment
    {
        // Prepare fields
        $inputs['user_id'] = $comment->user_id ?? Auth::id();
        $inputs['commentable_id'] = $model->id;
        $inputs['commentable_type'] = $model::class;
        // Save note
        $comment->fill($inputs)->save();
        // Upload files
        if ($uploadedFiles) {
            $this->handleUploadFiles($comment, $uploadedFiles);
        }

        return $comment;
    }

    /**
     * Upload comments files.
     */
    private function handleUploadFiles(Comment $comment, Array $uploadedFiles): void
    {
        foreach ($uploadedFiles as $uploadedFile) {
            $this->fileService->handleUpload($uploadedFile, 'comments', $comment);
        }
    }
}