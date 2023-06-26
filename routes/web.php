<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::middleware(['auth'])->group(function () {
    // Analysis
    Route::group(['prefix' => 'analysis', 'as' => 'analysis.'], function () {
        Route::get('/milestones', App\Http\Controllers\Analysis\MilestoneAnalysisController::class)->name('milestones');
        Route::get('/projects', App\Http\Controllers\Analysis\ProjectAnalysisController::class)->name('projects');
        Route::get('/tasks', App\Http\Controllers\Analysis\TaskAnalysisController::class)->name('tasks');
        Route::get('/tickets', App\Http\Controllers\Analysis\TicketAnalysisController::class)->name('tickets');
        Route::get('/timesheets', App\Http\Controllers\Analysis\TimesheetAnalysisController::class)->name('timesheets');
    });
    // Clients
    Route::group(['prefix' => 'clients/{client}', 'as' => 'clients.'], function () {
        // Actions
        Route::patch('/mark', App\Http\Controllers\Client\ClientMarkController::class)->name('mark');
        Route::delete('/logo/remove', App\Http\Controllers\Client\ClientLogoRemoveController::class)->name('logo.remove');
        // Comments
        Route::resource('comments', App\Http\Controllers\Client\ClientCommentController::class)
            ->only(['index', 'store', 'update', 'destroy']);
        // Files
        Route::group(['prefix' => '/files', 'as' => 'files.'], function () {
            Route::post('/upload', App\Http\Controllers\Client\File\ClientFileUploaderController::class)->name('upload');
            Route::delete('/{file}', App\Http\Controllers\Client\File\ClientFileDestroyController::class)->name('destroy');
            Route::get('/', [App\Http\Controllers\Client\File\ClienFileController::class, 'index'])->name('index');
        });
        // Notes
        Route::resource('notes', App\Http\Controllers\Client\Note\ClientNoteController::class)
            ->except(['show']);
    });
    Route::resource('clients', App\Http\Controllers\Client\ClientController::class);
    // Dashboard
    Route::group(['as' => 'dashboard.'], function () {
        Route::get('/', App\Http\Controllers\Dashboard\SummaryDashboardController::class)->name('index');
        Route::group(['prefix' => 'dashboard'], function () {
            Route::get('/projects', App\Http\Controllers\Dashboard\ProjectDashboardController::class)->name('projects');
            Route::get('/tasks', App\Http\Controllers\Dashboard\TaskDashboardController::class)->name('tasks');
            Route::get('/tickets', App\Http\Controllers\Dashboard\TicketDashboardController::class)->name('tickets');
        });
    });
    // Milestones
    Route::post('/milestones/load', App\Http\Controllers\MilestoneLoadByProjectController::class)->name('milestones.load');
    // Notes
    Route::patch('notes/{note}/mark', App\Http\Controllers\Note\NoteMarkController::class)->name('notes.mark');
    Route::resource('notes', App\Http\Controllers\Note\NoteController::class)
        ->except(['show']);
    // Releases
    Route::get('/releases', App\Http\Controllers\ReleaseController::class)->name('releases');
    // Reporting
    Route::group(['as' => 'reports.'], function () {
        Route::get('/report', App\Http\Controllers\Report\MenuReportController::class)->name('index');
        Route::group(['prefix' => 'report'], function () {
            Route::get('/milestones', App\Http\Controllers\Report\MilestoneReportController::class)->name('milestones');
            Route::get('/projects', App\Http\Controllers\Report\ProjectReportController::class)->name('projects');
            Route::get('/tasks', App\Http\Controllers\Report\TaskReportController::class)->name('tasks');
            Route::get('/tickets', App\Http\Controllers\Report\TicketReportController::class)->name('tickets');
            Route::get('/timesheets', App\Http\Controllers\Report\TimesheetReportController::class)->name('timesheets');
        });
    });
    // Tasks
    Route::group(['prefix' => 'tasks/{task}', 'as' => 'tasks.'], function () {
        // Actions
        Route::patch('/change-status', App\Http\Controllers\Task\TaskChangeStatusController::class)->name('change_status');
        Route::patch('/mark', App\Http\Controllers\Task\TaskMarkController::class)->name('mark');
        Route::patch('/pause', App\Http\Controllers\Task\TaskPauseController::class)->name('pause');
        // Comments
        Route::resource('comments', App\Http\Controllers\Task\TaskCommentController::class)
            ->only(['store', 'update', 'destroy']);
        // Files
        Route::group(['prefix' => '/files', 'as' => 'files.'], function () {
            Route::post('/upload', App\Http\Controllers\Task\TaskFileUploaderController::class)->name('upload');
            Route::delete('/{file}', App\Http\Controllers\Task\TaskFileDestroyController::class)->name('destroy');
        });
        // ToDos
        Route::patch('/todos/{todo}/check', App\Http\Controllers\Task\ToDo\TaskToDoCheckController::class)->name('todos.check');
        Route::resource('todos', App\Http\Controllers\Task\ToDo\TaskToDoController::class)
            ->except(['index', 'show']);
    });
    Route::resource('tasks', App\Http\Controllers\Task\TaskController::class);
    // Tickets
    Route::group(['prefix' => 'tickets/{ticket}', 'as' => 'tickets.'], function () {
        // Actions
        Route::patch('/convert', App\Http\Controllers\Ticket\TicketConvertTicketToTaskController::class)->name('convert_to_task');
        Route::patch('/change-status', App\Http\Controllers\Ticket\TicketChangeStatusController::class)->name('change_status');
        Route::patch('/mark', App\Http\Controllers\Ticket\TicketMarkController::class)->name('mark');
        // Comments
        Route::resource('comments', App\Http\Controllers\Ticket\TicketCommentController::class)
            ->only(['store', 'update', 'destroy']);
        // Files
        Route::group(['prefix' => '/files', 'as' => 'files.'], function () {
            Route::post('/upload', App\Http\Controllers\Ticket\TicketFileUploaderController::class)->name('upload');
            Route::delete('/{file}', App\Http\Controllers\Ticket\TicketFileDestroyController::class)->name('destroy');
        });
    });
    Route::resource('tickets', App\Http\Controllers\Ticket\TicketController::class);
    // Users
    Route::post('/users/load', App\Http\Controllers\UserLoadByProjectController::class)->name('users.load');
    Route::group(['prefix' => 'users', 'as' => 'users.'], function () {
        // Actions
        Route::delete('/{user}/avatar/remove', App\Http\Controllers\User\UserAvatarRemoveController::class)->name('avatar.remove');
        // Rates
        Route::get('/{user}/rates/assignment', App\Http\Controllers\User\Rate\UserRateAssignmentController::class)->name('rates.assignment');
        Route::post('/{user}/rates/assign', App\Http\Controllers\User\Rate\UserRateAssignController::class)->name('rates.assign');
        Route::resource('rates', App\Http\Controllers\User\Rate\UserRateController::class)
            ->except(['show']);
        // Roles
        Route::resource('roles', App\Http\Controllers\User\Role\UserRoleController::class)
            ->except(['show']);
    });
    Route::resource('users', App\Http\Controllers\User\UserController::class);
    // Projects
    Route::group(['prefix' => 'projects/{project}', 'as' => 'projects.'], function () {
        // Actions
        Route::patch('/change-status', App\Http\Controllers\Project\ProjectChangeStatusController::class)->name('change_status');
        Route::patch('/mark', App\Http\Controllers\Project\ProjectMarkController::class)->name('mark');
        // Comments
        Route::resource('comments', App\Http\Controllers\Project\ProjectCommentController::class)
            ->only(['index', 'store', 'update', 'destroy']);
        // Files
        Route::group(['prefix' => '/files', 'as' => 'files.'], function () {
            Route::post('/upload', App\Http\Controllers\Project\File\ProjectFileUploaderController::class)->name('upload');
            Route::delete('/{file}', App\Http\Controllers\Project\File\ProjectFileDestroyController::class)->name('destroy');
            Route::get('/', [App\Http\Controllers\Project\File\ProjectFileController::class, 'index'])->name('index');
        });
        // Milestones
        Route::group(['prefix' => '/milestone/{milestone}', 'as' => 'milestones.'], function () {
            // Actions
            Route::patch('/mark', App\Http\Controllers\Project\Milestone\ProjectMilestoneMarkController::class)->name('mark');
            // Comments
            Route::resource('comments', App\Http\Controllers\Project\Milestone\ProjectMilestoneCommentController::class)
                ->only(['store', 'update', 'destroy']);
            // Files
            Route::group(['prefix' => '/files', 'as' => 'files.'], function () {
                Route::post('/upload', App\Http\Controllers\Project\Milestone\ProjectMilestoneFileUploaderController::class)->name('upload');
                Route::delete('/{file}', App\Http\Controllers\Project\Milestone\ProjectMilestoneFileDestroyController::class)->name('destroy');
            });
        });
        Route::resource('milestones', App\Http\Controllers\Project\Milestone\ProjectMilestoneController::class);
        // Notes
        Route::resource('notes', App\Http\Controllers\Project\Note\ProjectNoteController::class)
            ->except(['show']);
        // Tasks
        Route::group(['prefix' => 'tasks', 'as' => 'tasks.'], function () {
            // Kanban
            Route::get('/kanban', App\Http\Controllers\Project\Task\ProjectTaskKanbanController::class)->name('kanban');
            Route::group(['prefix' => '{task}'], function () {
                // Comments
                Route::resource('comments', App\Http\Controllers\Project\Task\ProjectTaskCommentController::class)
                    ->only(['store', 'update', 'destroy']);
                // Files
                Route::group(['prefix' => '/files', 'as' => 'files.'], function () {
                    Route::post('/upload', App\Http\Controllers\Project\Task\ProjectTaskFileUploaderController::class)->name('upload');
                    Route::delete('/{file}', App\Http\Controllers\Project\Task\ProjectTaskFileDestroyController::class)->name('destroy');
                });
                // ToDos
                Route::resource('todos', App\Http\Controllers\Project\Task\ToDo\ProjectTaskToDoController::class)
                    ->except(['index', 'show']);
            });
        });
        Route::resource('tasks', App\Http\Controllers\Project\Task\ProjectTaskController::class)
            ->except(['destroy']);
        // Tickets
        Route::group(['prefix' => 'tickets/{ticket}', 'as' => 'tickets.'], function () {
            // Comments
            Route::resource('comments', App\Http\Controllers\Project\Ticket\ProjectTicketCommentController::class)
                ->only(['store', 'update', 'destroy']);
            // Files
            Route::group(['prefix' => '/files', 'as' => 'files.'], function () {
                Route::post('/upload', App\Http\Controllers\Project\Ticket\ProjectTicketFileUploaderController::class)->name('upload');
                Route::delete('/{file}', App\Http\Controllers\Project\Ticket\ProjectTicketFileDestroyController::class)->name('destroy');
            });
        });
        Route::resource('tickets', App\Http\Controllers\Project\Ticket\ProjectTicketController::class)
            ->except(['destroy']);
        // Timers
        Route::group(['prefix' => '/timers', 'as' => 'timers.'], function () {
            Route::post('/start', App\Http\Controllers\Project\Timer\ProjectTimerStartController::class)->name('start');
            Route::patch('/{timer}/stop', App\Http\Controllers\Project\Timer\ProjectTimerStopController::class)->name('stop');
        });
        Route::resource('timers', App\Http\Controllers\Project\Timer\ProjectTimerController::class)
            ->except(['show']);
    });
    Route::resource('projects', App\Http\Controllers\Project\ProjectController::class);
});
