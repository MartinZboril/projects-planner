<?php

use Illuminate\Support\Facades\{Auth, Route};

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
        // Comments
        Route::resource('comments', App\Http\Controllers\Client\ClientCommentController::class)
            ->except(['create', 'show', 'edit', 'destroy']);
        // Files
        Route::get('/files', [App\Http\Controllers\Client\ClienFileController::class, 'index'])->name('files.index');
        Route::post('/files/upload', App\Http\Controllers\Client\ClientFileUploaderController::class)->name('files.upload');
        // Notes
        Route::patch('/notes/{note}/mark', App\Http\Controllers\Client\ClientNoteMarkController::class)->name('notes.mark');
        Route::resource('notes', App\Http\Controllers\Client\ClientNoteController::class)
            ->except(['show', 'destroy']);
        // Marking
        Route::patch('/mark', App\Http\Controllers\Client\ClientMarkController::class)->name('mark');
    });
    Route::resource('clients', App\Http\Controllers\Client\ClientController::class)
        ->except(['destroy']);
    // Dashboard
    Route::group(['as' => 'dashboard.'], function () {
        Route::get('/', App\Http\Controllers\Dashboard\SummaryDashboardController::class)->name('index');
        Route::group(['prefix' => 'dashboard'], function () {
            Route::get('/projects', App\Http\Controllers\Dashboard\ProjectDashboardController::class)->name('projects');
            Route::get('/tasks', App\Http\Controllers\Dashboard\TaskDashboardController::class)->name('tasks');
            Route::get('/tickets', App\Http\Controllers\Dashboard\TicketDashboardController::class)->name('tickets');
        });
    });
    // Files
    Route::post('/files/upload', App\Http\Controllers\UploadFileController::class)->name('files.upload');
    // Notes
    Route::patch('notes/{note}/mark', App\Http\Controllers\Note\NoteMarkController::class)->name('notes.mark');
    Route::resource('notes', App\Http\Controllers\Note\NoteController::class)
        ->except(['show', 'destroy']);
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
            ->only(['store', 'update']);
        // Files
        Route::post('/files/upload', App\Http\Controllers\Task\TaskFileUploaderController::class)->name('files.upload');
        // ToDos
        Route::patch('/todos/{todo}/check', App\Http\Controllers\Task\TaskToDoCheckController::class)->name('todos.check');
        Route::resource('todos', App\Http\Controllers\Task\TaskToDoController::class)
            ->except(['index', 'show', 'destroy']);
    });
    Route::resource('tasks', App\Http\Controllers\Task\TaskController::class)
        ->except(['destroy']);
    // Tickets
    Route::group(['prefix' => 'tickets/{ticket}', 'as' => 'tickets.'], function () {
        // Actions
        Route::patch('/convert', App\Http\Controllers\Ticket\TicketConvertTicketToTaskController::class)->name('convert_to_task');
        Route::patch('/change-status', App\Http\Controllers\Ticket\TicketChangeStatusController::class)->name('change_status');
        Route::patch('/mark', App\Http\Controllers\Ticket\TicketMarkController::class)->name('mark');
        // Comments
        Route::resource('comments', App\Http\Controllers\Ticket\TicketCommentController::class)
            ->only(['store', 'update']);
        // Files
        Route::post('/files/upload', App\Http\Controllers\Ticket\TicketFileUploaderController::class)->name('files.upload');
    });
    Route::resource('tickets', App\Http\Controllers\Ticket\TicketController::class)
        ->except(['destroy']);
    // Users
    Route::group(['prefix' => 'users/{user}', 'as' => 'users.'], function () {
        // Rates
        Route::resource('rates', App\Http\Controllers\User\UserRateController::class)
            ->except(['index', 'show', 'destroy']);
    });
    Route::resource('users', App\Http\Controllers\User\UserController::class)
        ->except(['destroy']);
    // Projects
    Route::resource('projects', App\Http\Controllers\Project\ProjectController::class)
        ->except(['destroy']);
    Route::group(['prefix' => 'projects/{project}', 'as' => 'projects.'], function () {
        // Actions
        Route::patch('/change-status', App\Http\Controllers\Project\ProjectChangeStatusController::class)->name('change_status');
        Route::patch('/mark', App\Http\Controllers\Project\ProjectMarkController::class)->name('mark');
        // Comments
        Route::resource('comments', App\Http\Controllers\Project\ProjectCommentController::class)
            ->except(['create', 'show', 'edit', 'destroy']);
        // Files
        Route::get('/files', [App\Http\Controllers\Project\ProjectFileController::class, 'index'])->name('files.index');
        Route::post('/files/upload', App\Http\Controllers\Project\ProjectFileUploaderController::class)->name('files.upload');
        // Milestones
        Route::group(['prefix' => '/milestone/{milestone}', 'as' => 'milestones.'], function () {
            // Actions
            Route::patch('/mark', App\Http\Controllers\Project\ProjectMilestoneMarkController::class)->name('mark');
            // Comments
            Route::resource('comments', App\Http\Controllers\Project\ProjectMilestoneCommentController::class)
                ->only(['store', 'update']);
        });
        Route::resource('milestones', App\Http\Controllers\Project\ProjectMilestoneController::class)
            ->except(['destroy']);     
        // Notes
        Route::patch('/notes/{note}/mark', App\Http\Controllers\Project\ProjectNoteMarkController::class)->name('notes.mark');
        Route::resource('notes', App\Http\Controllers\Project\ProjectNoteController::class)
            ->except(['show', 'destroy']);
        // Tasks
        Route::group(['prefix' => 'tasks/{task}', 'as' => 'tasks.'], function () {
            // Actions
            Route::patch('/change-status', App\Http\Controllers\Project\ProjectTaskChangeStatusController::class)->name('change_status');
            Route::patch('/mark', App\Http\Controllers\Project\ProjectTaskMarkController::class)->name('mark');
            Route::patch('/pause', App\Http\Controllers\Project\ProjectTaskPauseController::class)->name('pause');
            // Comments
            Route::resource('comments', App\Http\Controllers\Project\ProjectTaskCommentController::class)
                ->only(['store', 'update']);
            // Files
            Route::post('/files/upload', App\Http\Controllers\Project\ProjectTaskFileUploaderController::class)->name('files.upload');
            // Kanban

            // ToDos
            Route::patch('/todos/{todo}/check', App\Http\Controllers\Project\ProjectTaskToDoCheckController::class)->name('todos.check');
            Route::resource('todos', App\Http\Controllers\Project\ProjectTaskToDoController::class)
                ->except(['index', 'show', 'destroy']);
        });
        Route::resource('tasks', App\Http\Controllers\Project\ProjectTaskController::class)
            ->except(['destroy']);
        // Tickets
        Route::group(['prefix' => 'tickets/{ticket}', 'as' => 'tickets.'], function () {
            // Actions
            Route::patch('/convert', App\Http\Controllers\Project\ProjectTicketConvertTicketToTaskController::class)->name('convert_to_task');
            Route::patch('/change-status', App\Http\Controllers\Project\ProjectTicketChangeStatusController::class)->name('change_status');
            Route::patch('/mark', App\Http\Controllers\Project\ProjectTicketMarkController::class)->name('mark');
            // Comments
            Route::resource('comments', App\Http\Controllers\Project\ProjectTicketCommentController::class)
                ->only(['store', 'update']);
            // Files
            Route::post('/files/upload', App\Http\Controllers\Project\ProjectTicketFileUploaderController::class)->name('files.upload');
        });
        Route::resource('tickets', App\Http\Controllers\Project\ProjectTicketController::class)
            ->except(['destroy']);        
        // Timers
        Route::group(['prefix' => '/timers', 'as' => 'timers.'], function () {
            Route::post('/start', App\Http\Controllers\Project\ProjectTimerStartController::class)->name('start');
            Route::patch('/{timer}/stop', App\Http\Controllers\Project\ProjectTimerStopController::class)->name('stop');
        });
        Route::resource('timers', App\Http\Controllers\Project\ProjectTimerController::class)
            ->except(['show', 'destroy']);
    });
});

// Projects

// Tasks
Route::get('/projects/{project}/kanban', [App\Http\Controllers\Data\ProjectController::class, 'kanban'])->name('projects.kanban');
Route::get('/projects/{project}/task/{task}/todo/create', [App\Http\Controllers\Data\ProjectController::class, 'createToDo'])->name('projects.todo.create');
Route::get('/projects/{project}/task/{task}/todo/{todo}/edit', [App\Http\Controllers\Data\ProjectController::class, 'editToDo'])->name('projects.todo.edit');