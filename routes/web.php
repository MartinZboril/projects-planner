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