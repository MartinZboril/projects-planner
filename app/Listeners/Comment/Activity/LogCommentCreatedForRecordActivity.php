<?php

namespace App\Listeners\Comment\Activity;

use App\Events\Comment\CommentCreated;
use App\Models\Project;
use App\Models\Ticket;

class LogCommentCreatedForRecordActivity
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

            case 'App\Models\Ticket':
                $model = Ticket::find($commentableId);
                $type = 'ticket';
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
