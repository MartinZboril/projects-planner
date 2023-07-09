<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        \App\Events\Comment\CommentCreated::class => [
            \App\Listeners\Comment\SendCommentCreatedNotification::class,
        ],
        \App\Events\Milestone\MilestoneOwnerChanged::class => [
            \App\Listeners\Milestone\SendMilestoneAssignmentNotifications::class,
        ],
        \App\Events\Project\ProjectTeamChanged::class => [
            \App\Listeners\Project\SendWelcomeToNewMembersNotification::class,
            \App\Listeners\Project\SendFarewellToOldMembersNotification::class,
        ],
        \App\Events\Project\Status\ProjectArchived::class => [
            \App\Listeners\Project\Status\SendProjectArchivedNotification::class,
        ],
        \App\Events\Project\Status\ProjectFinished::class => [
            \App\Listeners\Project\Status\SendProjectFinishedNotification::class,
        ],
        \App\Events\Project\Status\ProjectReactived::class => [
            \App\Listeners\Project\Status\SendProjectReactivedNotification::class,
        ],
        \App\Events\Task\TaskUserChanged::class => [
            \App\Listeners\Task\SendTaskAssignmentNotifications::class,
        ],
        \App\Events\Task\Status\TaskCompleted::class => [
            \App\Listeners\Task\Status\SendTaskCompletedNotification::class,
        ],
        \App\Events\Task\Status\TaskInProgressed::class => [
            \App\Listeners\Task\Status\SendTaskInProgressedNotification::class,
        ],
        \App\Events\Task\Status\TaskReturned::class => [
            \App\Listeners\Task\Status\SendTaskReturnedNotification::class,
        ],
        \App\Events\Task\Status\TaskPaused::class => [
            \App\Listeners\Task\Status\SendTaskPausedNotification::class,
        ],
        \App\Events\Task\Status\TaskResumed::class => [
            \App\Listeners\Task\Status\SendTaskResumedNotification::class,
        ],
        \App\Events\Ticket\TicketAssigneeChanged::class => [
            \App\Listeners\Ticket\SendTicketAssignmentNotifications::class,
        ],
        \App\Events\Ticket\Status\TicketArchived::class => [
            \App\Listeners\Ticket\Status\SendTicketArchivedNotification::class,
        ],
        \App\Events\Ticket\Status\TicketClosed::class => [
            \App\Listeners\Ticket\Status\SendTicketClosedNotification::class,
        ],
        \App\Events\Ticket\Status\TicketConverted::class => [
            \App\Listeners\Ticket\Status\SendTicketConvertedNotification::class,
        ],
        \App\Events\Ticket\Status\TicketReopened::class => [
            \App\Listeners\Ticket\Status\SendTicketReopenedNotification::class,
        ],
        \App\Events\User\UserCreated::class => [
            \App\Listeners\User\SendUserCreatedNotification::class,
        ],
        \App\Events\User\UserDeleted::class => [
            \App\Listeners\User\SendUserDeletedNotification::class,
        ],
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        \App\Models\Client::observe(\App\Observers\ClientObserver::class);
        \App\Models\Comment::observe(\App\Observers\CommentObserver::class);
        \App\Models\Milestone::observe(\App\Observers\MilestoneObserver::class);
        \App\Models\Project::observe(\App\Observers\ProjectObserver::class);
        \App\Models\Role::observe(\App\Observers\RoleObserver::class);
        \App\Models\Task::observe(\App\Observers\TaskObserver::class);
        \App\Models\Ticket::observe(\App\Observers\TicketObserver::class);
        \App\Models\User::observe(\App\Observers\UserObserver::class);
    }
}
