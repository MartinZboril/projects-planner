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
        \App\Events\CommentCreated::class => [
            \App\Listeners\SendCommentCreatedNotification::class,
        ],
        \App\Events\Milestone\MilestoneOwnerChanged::class => [
            \App\Listeners\Milestone\SendAssignmentNotifications::class,
        ],
        \App\Events\ProjectTeamChanged::class => [
            \App\Listeners\Project\SendWelcomeToNewMembersNotification::class,
            \App\Listeners\Project\SendFarewellToOldMembersNotification::class,
        ],
        \App\Events\Task\TaskUserChanged::class => [
            \App\Listeners\Task\SendAssignmentNotifications::class,
        ],
        \App\Events\Ticket\TicketAssigneeChanged::class => [
            \App\Listeners\Ticket\SendAssignmentNotifications::class,
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
