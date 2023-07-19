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
            \App\Listeners\Comment\Activity\LogCommentCreatedForRecordActivity::class,
        ],

        \App\Events\Milestone\MilestoneCreated::class => [
            \App\Listeners\Milestone\Activity\LogMilestoneCreatedForProjectActivity::class,
        ],

        \App\Events\Milestone\MilestoneDeleted::class => [
            \App\Listeners\Milestone\Activity\LogMilestoneDeletedForProjectActivity::class,
        ],

        \App\Events\Milestone\MilestoneOwnerChanged::class => [
            \App\Listeners\Milestone\SendMilestoneAssignmentNotifications::class,
        ],

        \App\Events\Project\ProjectTeamChanged::class => [
            \App\Listeners\Project\SendWelcomeToNewMembersNotification::class,
            \App\Listeners\Project\SendFarewellToOldMembersNotification::class,
        ],

        \App\Events\Project\ProjectDeleted::class => [
            \App\Listeners\Project\SendProjectDeletedNotification::class,
        ],

        \App\Events\Project\Status\ProjectArchived::class => [
            \App\Listeners\Project\Status\SendProjectArchivedNotification::class,
            \App\Listeners\Project\Activity\LogProjectArchivedActivity::class,
        ],

        \App\Events\Project\Status\ProjectFinished::class => [
            \App\Listeners\Project\Status\SendProjectFinishedNotification::class,
            \App\Listeners\Project\Activity\LogProjectFinishedActivity::class,
        ],

        \App\Events\Project\Status\ProjectReactived::class => [
            \App\Listeners\Project\Status\SendProjectReactivedNotification::class,
            \App\Listeners\Project\Activity\LogProjectReactivedActivity::class,
        ],

        \App\Events\Task\TaskUserChanged::class => [
            \App\Listeners\Task\SendTaskAssignmentNotifications::class,
        ],

        \App\Events\Task\TaskMilestoneChanged::class => [
            \App\Listeners\Task\Activity\LogTaskAssignForMilestoneActivity::class,
            \App\Listeners\Task\Activity\LogTaskUnassignForMilestoneActivity::class,
        ],

        \App\Events\Task\TaskCreated::class => [
            \App\Listeners\Task\Activity\LogTaskCreatedForProjectActivity::class,
        ],

        \App\Events\Task\TaskDeleted::class => [
            \App\Listeners\Task\SendTaskDeletedNotification::class,
            \App\Listeners\Task\Activity\LogTaskDeletedForProjectActivity::class,
        ],

        \App\Events\Task\Status\TaskCompleted::class => [
            \App\Listeners\Task\Status\SendTaskCompletedNotification::class,
            \App\Listeners\Task\Activity\LogTaskCompletedActivity::class,
        ],

        \App\Events\Task\Status\TaskInProgressed::class => [
            \App\Listeners\Task\Status\SendTaskInProgressedNotification::class,
            \App\Listeners\Task\Activity\LogTaskInProgressedActivity::class,
        ],

        \App\Events\Task\Status\TaskReturned::class => [
            \App\Listeners\Task\Status\SendTaskReturnedNotification::class,
            \App\Listeners\Task\Activity\LogTaskReturnedActivity::class,
        ],

        \App\Events\Task\Status\TaskPaused::class => [
            \App\Listeners\Task\Status\SendTaskPausedNotification::class,
            \App\Listeners\Task\Activity\LogTaskPausedActivity::class,
        ],

        \App\Events\Task\Status\TaskResumed::class => [
            \App\Listeners\Task\Status\SendTaskResumedNotification::class,
            \App\Listeners\Task\Activity\LogTaskResumedActivity::class,
        ],

        \App\Events\Ticket\TicketAssigneeChanged::class => [
            \App\Listeners\Ticket\SendTicketAssignmentNotifications::class,
        ],

        \App\Events\Ticket\TicketDeleted::class => [
            \App\Listeners\Ticket\SendTicketDeletedNotification::class,
            \App\Listeners\Ticket\Activity\LogTicketDeletedForProjectActivity::class,
        ],

        \App\Events\Ticket\TicketCreated::class => [
            \App\Listeners\Ticket\Activity\LogTicketCreatedForProjectActivity::class,
        ],

        \App\Events\Ticket\Status\TicketArchived::class => [
            \App\Listeners\Ticket\Status\SendTicketArchivedNotification::class,
            \App\Listeners\Ticket\Activity\LogTicketArchivedActivity::class,
        ],

        \App\Events\Ticket\Status\TicketClosed::class => [
            \App\Listeners\Ticket\Status\SendTicketClosedNotification::class,
            \App\Listeners\Ticket\Activity\LogTicketClosedActivity::class,
        ],

        \App\Events\Ticket\Status\TicketConverted::class => [
            \App\Listeners\Ticket\Status\SendTicketConvertedNotification::class,
            \App\Listeners\Ticket\Activity\LogTicketConvertedActivity::class,
        ],

        \App\Events\Ticket\Status\TicketReopened::class => [
            \App\Listeners\Ticket\Status\SendTicketReopenedNotification::class,
            \App\Listeners\Ticket\Activity\LogTicketReopenedActivity::class,
        ],

        \App\Events\User\UserCreated::class => [
            \App\Listeners\User\SendUserCreatedNotification::class,
        ],

        \App\Events\User\UserDeleted::class => [
            \App\Listeners\User\SendUserDeletedNotification::class,
            \App\Listeners\Task\SendTasksWithoutUserNotification::class,
            \App\Listeners\Ticket\SendTicketsWithoutAssigneeNotification::class,
        ],

        \App\Events\Timer\TimerStopped::class => [
            \App\Listeners\Timer\Activity\LogTimerStoppedForProjectActivity::class,
        ],

        \App\Events\Timer\TimerChanged::class => [
            \App\Listeners\Timer\Activity\LogTimerChangedForProjectActivity::class,
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
