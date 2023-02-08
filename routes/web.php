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

// Tasks
Route::get('/tasks', [App\Http\Controllers\Data\TaskController::class, 'index'])->name('tasks.index');
Route::get('/tasks/create', [App\Http\Controllers\Data\TaskController::class, 'create'])->name('tasks.create');
Route::get('/tasks/{task}/detail', [App\Http\Controllers\Data\TaskController::class, 'detail'])->name('tasks.detail');
Route::get('/tasks/{task}/edit', [App\Http\Controllers\Data\TaskController::class, 'edit'])->name('tasks.edit');
Route::get('/tasks/{task}/todos/create', [App\Http\Controllers\Data\ToDoController::class, 'create'])->name('todos.create');
Route::get('/tasks/{task}/todos/{todo}/edit', [App\Http\Controllers\Data\ToDoController::class, 'edit'])->name('todos.edit');

Route::post('/tasks/store', [App\Http\Controllers\Data\TaskController::class, 'store'])->name('tasks.store');
Route::patch('/tasks/{task}/update', [App\Http\Controllers\Data\TaskController::class, 'update'])->name('tasks.update');
Route::patch('/tasks/{task}/change', [App\Http\Controllers\Data\TaskController::class, 'change'])->name('tasks.change');
Route::patch('/tasks/{task}/pause', [App\Http\Controllers\Data\TaskController::class, 'pause'])->name('tasks.pause');
Route::patch('/tasks/{task}/mark', [App\Http\Controllers\Data\TaskController::class, 'mark'])->name('tasks.mark');

// Milestones
Route::post('/milestones/store', [App\Http\Controllers\Data\MilestoneController::class, 'store'])->name('milestones.store');
Route::patch('/milestones/{milestone}/update', [App\Http\Controllers\Data\MilestoneController::class, 'update'])->name('milestones.update');
Route::patch('/milestones/{milestone}/mark', [App\Http\Controllers\Data\MilestoneController::class, 'mark'])->name('milestones.mark');

// ToDos
Route::post('/todos/store', [App\Http\Controllers\Data\ToDoController::class, 'store'])->name('todos.store');
Route::patch('/todos/{todo}/update', [App\Http\Controllers\Data\ToDoController::class, 'update'])->name('todos.update');
Route::patch('/todos/{todo}/check', [App\Http\Controllers\Data\ToDoController::class, 'check'])->name('todos.check');

// Timers
Route::post('/timers/store', [App\Http\Controllers\Data\TimerController::class, 'store'])->name('timers.store');
Route::post('/timers/start', [App\Http\Controllers\Data\TimerController::class, 'start'])->name('timers.start');
Route::patch('/timers/{timer}/stop', [App\Http\Controllers\Data\TimerController::class, 'stop'])->name('timers.stop');
Route::patch('/timers/{timer}/update', [App\Http\Controllers\Data\TimerController::class, 'update'])->name('timers.update');

// Tickets
Route::get('/tickets', [App\Http\Controllers\Data\TicketController::class, 'index'])->name('tickets.index');
Route::get('/tickets/create', [App\Http\Controllers\Data\TicketController::class, 'create'])->name('tickets.create');
Route::get('/tickets/{ticket}/detail', [App\Http\Controllers\Data\TicketController::class, 'detail'])->name('tickets.detail');
Route::get('/tickets/{ticket}/edit', [App\Http\Controllers\Data\TicketController::class, 'edit'])->name('tickets.edit');

Route::post('/tickets/store', [App\Http\Controllers\Data\TicketController::class, 'store'])->name('tickets.store');
Route::patch('/tickets/{ticket}/update', [App\Http\Controllers\Data\TicketController::class, 'update'])->name('tickets.update');
Route::patch('/tickets/{ticket}/change', [App\Http\Controllers\Data\TicketController::class, 'change'])->name('tickets.change');
Route::patch('/tickets/{ticket}/convert', [App\Http\Controllers\Data\TicketController::class, 'convert'])->name('tickets.convert');
Route::patch('/tickets/{ticket}/mark', [App\Http\Controllers\Data\TicketController::class, 'mark'])->name('tickets.mark');

// Comments
Route::post('/comments/store', [App\Http\Controllers\Data\CommentController::class, 'store'])->name('comments.store');
Route::patch('/comment/{comment}/update', [App\Http\Controllers\Data\CommentController::class, 'update'])->name('comments.update');