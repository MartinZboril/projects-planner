<?php

namespace App\Listeners\Comment;

use App\Events\Comment\CommentCreated;
use App\Models\Milestone;
use App\Models\Project;
use App\Models\Task;
use App\Models\Ticket;
use App\Notifications\Comment\NewlyCreatedCommentNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendCommentCreatedNotification implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     */
    public function handle(CommentCreated $event): void
    {
        $comment = $event->comment;
        $commentableType = $event->comment->commentable_type;
        $commentableId = $event->comment->commentable_id;

        $notifiers = [];
        $detail = '';
        $data = [];

        switch ($commentableType) {
            case 'App\Models\Project':
                $project = Project::find($commentableId);
                $notifiers = $project->team;
                $detail = route('projects.comments.index', $project);
                $data = (object) [
                    'name' => $project->name,
                    'type' => 'project',
                ];
                break;

            case 'App\Models\Milestone':
                $milestone = Milestone::find($commentableId);
                $notifiers = [$milestone->owner];
                $detail = route('projects.milestones.show', ['project' => $milestone->project, 'milestone' => $milestone->id]);
                $data = (object) [
                    'name' => $milestone->name,
                    'type' => 'milestone',
                ];
                break;

            case 'App\Models\Task':
                $task = Task::find($commentableId);
                $notifiers = [$task->author, $task->user];
                $detail = route('tasks.show', $task);
                $data = (object) [
                    'name' => $task->name,
                    'type' => 'task',
                ];
                break;

            case 'App\Models\Ticket':
                $ticket = Ticket::find($commentableId);
                $notifiers = [$ticket->reporter];
                $ticket->assignee ? array_push($notifiers, $ticket->assignee) : null;
                $detail = route('tickets.show', $ticket);
                $data = (object) [
                    'name' => $ticket->subject,
                    'type' => 'ticket',
                ];
                break;
        }

        $object = (object) [
            'detail' => $detail,
            'data' => (object) $data,
        ];

        foreach ($notifiers as $notifier) {
            if (! $notifier->trashed()) {
                $notifier->notify(new NewlyCreatedCommentNotification($comment, $object));
            }
        }
    }
}
