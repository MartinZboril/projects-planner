<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Users
Route::get('/users', [App\Http\Controllers\UserController::class, 'index'])->name('users.index');
Route::get('/users/create', [App\Http\Controllers\UserController::class, 'create'])->name('users.create');
Route::get('/users/{user}/detail', [App\Http\Controllers\UserController::class, 'detail'])->name('users.detail');
Route::get('/users/{user}/edit', [App\Http\Controllers\UserController::class, 'edit'])->name('users.edit');

Route::post('/users/store', [App\Http\Controllers\UserController::class, 'store'])->name('users.store');
Route::patch('/users/{user}/update', [App\Http\Controllers\UserController::class, 'update'])->name('users.update');

// Clients
Route::get('/clients', [App\Http\Controllers\ClientController::class, 'index'])->name('clients.index');
Route::get('/clients/create', [App\Http\Controllers\ClientController::class, 'create'])->name('clients.create');
Route::get('/clients/{client}/detail', [App\Http\Controllers\ClientController::class, 'detail'])->name('clients.detail');
Route::get('/clients/{client}/edit', [App\Http\Controllers\ClientController::class, 'edit'])->name('clients.edit');

Route::post('/clients/store', [App\Http\Controllers\ClientController::class, 'store'])->name('clients.store');
Route::patch('/clients/{client}/update', [App\Http\Controllers\ClientController::class, 'update'])->name('clients.update');

// Projects
Route::get('/projects', [App\Http\Controllers\ProjectController::class, 'index'])->name('projects.index');
Route::get('/projects/create', [App\Http\Controllers\ProjectController::class, 'create'])->name('projects.create');
Route::get('/projects/{project}/detail', [App\Http\Controllers\ProjectController::class, 'detail'])->name('projects.detail');
Route::get('/projects/{project}/edit', [App\Http\Controllers\ProjectController::class, 'edit'])->name('projects.edit');
Route::get('/projects/{project}/tasks', [App\Http\Controllers\ProjectController::class, 'tasks'])->name('projects.tasks');

Route::post('/projects/store', [App\Http\Controllers\ProjectController::class, 'store'])->name('projects.store');
Route::patch('/projects/{project}/update', [App\Http\Controllers\ProjectController::class, 'update'])->name('projects.update');

// Tasks
Route::get('/tasks', [App\Http\Controllers\TaskController::class, 'index'])->name('tasks.index');
Route::get('/tasks/create', [App\Http\Controllers\TaskController::class, 'create'])->name('tasks.create');
Route::get('/tasks/{task}/detail', [App\Http\Controllers\TaskController::class, 'detail'])->name('tasks.detail');
Route::get('/tasks/{task}/edit', [App\Http\Controllers\TaskController::class, 'edit'])->name('tasks.edit');

Route::post('/tasks/store', [App\Http\Controllers\TaskController::class, 'store'])->name('tasks.store');
Route::patch('/tasks/{task}/update', [App\Http\Controllers\TaskController::class, 'update'])->name('tasks.update');