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
    Route::resource('clients', App\Http\Controllers\Client\ClientController::class)
        ->except(['destroy']);
    // Clients Additions
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
    Route::resource('tasks', App\Http\Controllers\Task\TaskController::class);
    // Tasks Additions
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
    // Tickets
    Route::resource('tickets', App\Http\Controllers\Ticket\TicketController::class)
        ->except(['destroy']);
    // Tickets Additions
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
    // Users
    Route::resource('users', App\Http\Controllers\User\UserController::class)
        ->except(['destroy']);
    // Users Additions
    Route::group(['prefix' => 'users/{user}', 'as' => 'users.'], function () {
        // Rates
        Route::resource('rates', App\Http\Controllers\User\UserRateController::class)
            ->except(['index', 'show', 'destroy']);
    });
});

// Projects
Route::get('/projects', [App\Http\Controllers\Data\ProjectController::class, 'index'])->name('projects.index');
Route::get('/projects/create', [App\Http\Controllers\Data\ProjectController::class, 'create'])->name('projects.create');
Route::get('/projects/{project}/detail', [App\Http\Controllers\Data\ProjectController::class, 'detail'])->name('projects.detail');
Route::get('/projects/{project}/edit', [App\Http\Controllers\Data\ProjectController::class, 'edit'])->name('projects.edit');
Route::get('/projects/{project}/tasks', [App\Http\Controllers\Data\ProjectController::class, 'tasks'])->name('projects.tasks');
Route::get('/projects/{project}/task/create', [App\Http\Controllers\Data\ProjectController::class, 'createTask'])->name('projects.task.create');
Route::get('/projects/{project}/task/{task}/detail', [App\Http\Controllers\Data\ProjectController::class, 'detailTask'])->name('projects.task.detail');
Route::get('/projects/{project}/task/{task}/edit', [App\Http\Controllers\Data\ProjectController::class, 'editTask'])->name('projects.task.edit');
Route::get('/projects/{project}/kanban', [App\Http\Controllers\Data\ProjectController::class, 'kanban'])->name('projects.kanban');
Route::get('/projects/{project}/milestones', [App\Http\Controllers\Data\ProjectController::class, 'milestones'])->name('projects.milestones');
Route::get('/projects/{project}/task/{task}/todo/create', [App\Http\Controllers\Data\ProjectController::class, 'createToDo'])->name('projects.todo.create');
Route::get('/projects/{project}/task/{task}/todo/{todo}/edit', [App\Http\Controllers\Data\ProjectController::class, 'editToDo'])->name('projects.todo.edit');
Route::get('/projects/{project}/milestones/create', [App\Http\Controllers\Data\MilestoneController::class, 'create'])->name('projects.milestones.create');
Route::get('/projects/{project}/milestones/{milestone}/detail', [App\Http\Controllers\Data\MilestoneController::class, 'detail'])->name('projects.milestones.detail');
Route::get('/projects/{project}/milestones/{milestone}/edit', [App\Http\Controllers\Data\MilestoneController::class, 'edit'])->name('projects.milestones.edit');
Route::get('/projects/{project}/timesheets', [App\Http\Controllers\Data\ProjectController::class, 'timesheets'])->name('projects.timesheets');
Route::get('/projects/{project}/timers/create', [App\Http\Controllers\Data\TimerController::class, 'create'])->name('projects.timers.create');
Route::get('/projects/{project}/timers/{timer}/edit', [App\Http\Controllers\Data\TimerController::class, 'edit'])->name('projects.timers.edit');
Route::get('/projects/{project}/tickets', [App\Http\Controllers\Data\ProjectController::class, 'tickets'])->name('projects.tickets');
Route::get('/projects/{project}/ticket/create', [App\Http\Controllers\Data\ProjectController::class, 'createTicket'])->name('projects.ticket.create');
Route::get('/projects/{project}/ticket/{ticket}/detail', [App\Http\Controllers\Data\ProjectController::class, 'detailTicket'])->name('projects.ticket.detail');
Route::get('/projects/{project}/ticket/{ticket}/edit', [App\Http\Controllers\Data\ProjectController::class, 'editTicket'])->name('projects.ticket.edit');
Route::get('/projects/{project}/notes', [App\Http\Controllers\Data\ProjectController::class, 'notes'])->name('projects.notes');
Route::get('/projects/{project}/note/create', [App\Http\Controllers\Data\ProjectController::class, 'createNote'])->name('projects.note.create');
Route::get('/projects/{project}/note/{note}/edit', [App\Http\Controllers\Data\ProjectController::class, 'editNote'])->name('projects.note.edit');
Route::get('/projects/{project}/files', [App\Http\Controllers\Data\ProjectController::class, 'files'])->name('projects.files');
Route::get('/projects/{project}/comments', [App\Http\Controllers\Data\ProjectController::class, 'comments'])->name('projects.comments');

Route::post('/projects/store', [App\Http\Controllers\Data\ProjectController::class, 'store'])->name('projects.store');
Route::patch('/projects/{project}/update', [App\Http\Controllers\Data\ProjectController::class, 'update'])->name('projects.update');
Route::patch('/projects/{project}/change', [App\Http\Controllers\Data\ProjectController::class, 'change'])->name('projects.change');
Route::patch('/projects/{project}/mark', [App\Http\Controllers\Data\ProjectController::class, 'mark'])->name('projects.mark');

// Milestones
Route::post('/milestones/store', [App\Http\Controllers\Data\MilestoneController::class, 'store'])->name('milestones.store');
Route::patch('/milestones/{milestone}/update', [App\Http\Controllers\Data\MilestoneController::class, 'update'])->name('milestones.update');
Route::patch('/milestones/{milestone}/mark', [App\Http\Controllers\Data\MilestoneController::class, 'mark'])->name('milestones.mark');

// Timers
Route::post('/timers/store', [App\Http\Controllers\Data\TimerController::class, 'store'])->name('timers.store');
Route::post('/timers/start', [App\Http\Controllers\Data\TimerController::class, 'start'])->name('timers.start');
Route::patch('/timers/{timer}/stop', [App\Http\Controllers\Data\TimerController::class, 'stop'])->name('timers.stop');
Route::patch('/timers/{timer}/update', [App\Http\Controllers\Data\TimerController::class, 'update'])->name('timers.update');