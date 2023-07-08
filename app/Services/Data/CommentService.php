<?php

namespace App\Services\Data;

use App\Models\Comment;
use App\Notifications\Comment\NewlyCreatedCommentNotification;
use App\Services\FileService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CommentService
{
    public function __construct(
        private FileService $fileService,
    ) {
    }

    /**
     * Save data for comment.
     */
    public function handleSave(Comment $comment, array $inputs, Model $model, ?array $uploadedFiles): Comment
    {
        $creatingMode = $comment->exists ? false : true;
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
        // Notify users
        if ($creatingMode) {
            $this->notifyUsers($comment, $model);
        }

        return $comment;
    }

    private function notifyUsers(Comment $comment, Model $model): void
    {
        $notifiers = [];
        $detail = '';
        $object = [];

        switch ($model::class) {
            case 'App\Models\Project':
                $notifiers = $model->team;
                $detail = route('projects.show', $model);
                $object = [
                    'name' => $model->name,
                    'type' => 'project',
                ];
                break;

            case 'App\Models\Milestone':
                array_push($notifiers, $model->owner);
                $detail = route('projects.milestones.show', ['project' => $model->project, 'milestone' => $model->id]);
                $object = [
                    'name' => $model->name,
                    'type' => 'milestone',
                ];
                break;

            case 'App\Models\Task':
                array_push($notifiers, $model->author);
                array_push($notifiers, $model->user);
                $detail = route('tasks.show', $model);
                $object = [
                    'name' => $model->name,
                    'type' => 'task',
                ];
                break;

            case 'App\Models\Ticket':
                array_push($notifiers, $model->reporter);
                $model->assignee ? array_push($notifiers, $model->assignee) : null;
                $detail = route('tickets.show', $model);
                $object = [
                    'name' => $model->subject,
                    'type' => 'ticket',
                ];
                break;
        }

        foreach ($notifiers as $notifier) {
            if (! $notifier->trashed()) {
                $notifier->notify(new NewlyCreatedCommentNotification($comment, $detail, $object));
            }
        }
    }

    /**
     * Upload comments files.
     */
    private function handleUploadFiles(Comment $comment, array $uploadedFiles): void
    {
        foreach ($uploadedFiles as $uploadedFile) {
            $this->fileService->handleUpload($uploadedFile, 'comments', $comment);
        }
    }

    /**
     * Delete selected comment.
     */
    public function handleDelete(Comment $comment): void
    {
        $comment->delete();
    }
}
