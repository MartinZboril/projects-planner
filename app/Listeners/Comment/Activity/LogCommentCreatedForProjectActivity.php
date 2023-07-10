<?php

namespace App\Listeners\Comment\Activity;

use App\Events\Comment\CommentCreated;
use App\Models\Project;

class LogCommentCreatedForProjectActivity
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(CommentCreated $event): void
    {
        $commentableType = $event->comment->commentable_type;
        $commentableId = $event->comment->commentable_id;

        $model = null;
        $type = null;

        switch ($commentableType) {
            case 'App\Models\Project':
                $model = Project::find($commentableId);
                $type = 'project';
                break;
        }

        if (! $model || ! $type) {
            return;
        }

        activity()
            ->performedOn($model)
            ->log("New comment posted to the {$type}");
    }
}
