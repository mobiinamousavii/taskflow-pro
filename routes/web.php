<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\WorkspaceController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class , 'index'])->name('welcome');

Route::middleware('guest')->group(function () {
Route::get('/register', [AuthController::class,'showregister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showlogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
});
Route::middleware(['auth'])->group(function () {
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::resource('workspaces', WorkspaceController::class)->names('workspaces');
Route::resource('projects', ProjectController::class)->names('projects');
Route::resource('tasks', TaskController::class)->names('tasts');
});
Route::post('/logout' ,[AuthController::class,'logout'])->name('logout');