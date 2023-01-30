<?php

use Illuminate\Support\Facades\{Auth, Route};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

// Dashboard
Route::get('/', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard.index');
Route::get('/dashboard/projects', [App\Http\Controllers\DashboardController::class, 'projects'])->name('dashboard.projects');
Route::get('/dashboard/tasks', [App\Http\Controllers\DashboardController::class, 'tasks'])->name('dashboard.tasks');
Route::get('/dashboard/tickets', [App\Http\Controllers\DashboardController::class, 'tickets'])->name('dashboard.tickets');

// Releases
Route::get('/releases', [App\Http\Controllers\ReleaseController::class, 'index'])->name('releases.index');

// Users
Route::get('/users', [App\Http\Controllers\Data\UserController::class, 'index'])->name('users.index');
Route::get('/users/create', [App\Http\Controllers\Data\UserController::class, 'create'])->name('users.create');
Route::get('/users/{user}/detail', [App\Http\Controllers\Data\UserController::class, 'detail'])->name('users.detail');
Route::get('/users/{user}/edit', [App\Http\Controllers\Data\UserController::class, 'edit'])->name('users.edit');
Route::get('/users/{user}/rates/create', [App\Http\Controllers\Data\RateController::class, 'create'])->name('rates.create');
Route::get('/users/{user}/rates/{rate}/edit', [App\Http\Controllers\Data\RateController::class, 'edit'])->name('rates.edit');

Route::post('/users/store', [App\Http\Controllers\Data\UserController::class, 'store'])->name('users.store');
Route::patch('/users/{user}/update', [App\Http\Controllers\Data\UserController::class, 'update'])->name('users.update');

// Clients
Route::get('/clients', [App\Http\Controllers\Data\ClientController::class, 'index'])->name('clients.index');
Route::get('/clients/create', [App\Http\Controllers\Data\ClientController::class, 'create'])->name('clients.create');
Route::get('/clients/{client}/detail', [App\Http\Controllers\Data\ClientController::class, 'detail'])->name('clients.detail');
Route::get('/clients/{client}/edit', [App\Http\Controllers\Data\ClientController::class, 'edit'])->name('clients.edit');
Route::get('/clients/{client}/notes', [App\Http\Controllers\Data\ClientController::class, 'notes'])->name('clients.notes');
Route::get('/clients/{client}/note/create', [App\Http\Controllers\Data\ClientController::class, 'createNote'])->name('clients.note.create');
Route::get('/clients/{client}/note/{note}/edit', [App\Http\Controllers\Data\ClientController::class, 'editNote'])->name('clients.note.edit');
Route::get('/clients/{client}/comments', [App\Http\Controllers\Data\ClientController::class, 'comments'])->name('clients.comments');
Route::get('/clients/{client}/files', [App\Http\Controllers\Data\ClientController::class, 'files'])->name('clients.files');

Route::post('/clients/store', [App\Http\Controllers\Data\ClientController::class, 'store'])->name('clients.store');
Route::patch('/clients/{client}/update', [App\Http\Controllers\Data\ClientController::class, 'update'])->name('clients.update');
Route::patch('/clients/{client}/mark', [App\Http\Controllers\Data\ClientController::class, 'mark'])->name('clients.mark');

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

// Rates
Route::post('/rates/store', [App\Http\Controllers\Data\RateController::class, 'store'])->name('rates.store');
Route::patch('/rates/{rate}/update', [App\Http\Controllers\Data\RateController::class, 'update'])->name('rates.update');

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

// Reporting
Route::get('/report', [App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
Route::get('/report/projects', [App\Http\Controllers\ReportController::class, 'projects'])->name('reports.projects');
Route::get('/report/tasks', [App\Http\Controllers\ReportController::class, 'tasks'])->name('reports.tasks');
Route::get('/report/tickets', [App\Http\Controllers\ReportController::class, 'tickets'])->name('reports.tickets');
Route::get('/report/milestones', [App\Http\Controllers\ReportController::class, 'milestones'])->name('reports.milestones');
Route::get('/report/timesheets', [App\Http\Controllers\ReportController::class, 'timesheets'])->name('reports.timesheets');

// Analysis
Route::get('/analyze/projects', [App\Http\Controllers\AnalyticsController::class, 'projects'])->name('analysis.projects');
Route::get('/analyze/tasks', [App\Http\Controllers\AnalyticsController::class, 'tasks'])->name('analysis.tasks');
Route::get('/analyze/tickets', [App\Http\Controllers\AnalyticsController::class, 'tickets'])->name('analysis.tickets');
Route::get('/analyze/milestones', [App\Http\Controllers\AnalyticsController::class, 'milestones'])->name('analysis.milestones');
Route::get('/analyze/timesheets', [App\Http\Controllers\AnalyticsController::class, 'timesheets'])->name('analysis.timesheets');

// Notes
Route::get('/notes', [App\Http\Controllers\Data\NoteController::class, 'index'])->name('notes.index');
Route::get('/notes/create', [App\Http\Controllers\Data\NoteController::class, 'create'])->name('notes.create');
Route::get('/notes/{note}/edit', [App\Http\Controllers\Data\NoteController::class, 'edit'])->name('notes.edit');

Route::post('/notes/store', [App\Http\Controllers\Data\NoteController::class, 'store'])->name('notes.store');
Route::patch('/notes/{note}/update', [App\Http\Controllers\Data\NoteController::class, 'update'])->name('notes.update');
Route::patch('/notes/{note}/mark', [App\Http\Controllers\Data\NoteController::class, 'mark'])->name('notes.mark');

// Comments
Route::post('/comments/store', [App\Http\Controllers\Data\CommentController::class, 'store'])->name('comments.store');
Route::patch('/comment/{comment}/update', [App\Http\Controllers\Data\CommentController::class, 'update'])->name('comments.update');

// Files
Route::post('/files/upload', [App\Http\Controllers\FileController::class, 'upload'])->name('files.upload');