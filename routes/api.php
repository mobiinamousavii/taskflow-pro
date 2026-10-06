<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    Route::get('dashboard', DashboardController::class , 'index');
    Route::apiResource('tasks', TaskController::class);
    Route::apiResource('projects', ProjectController::class);
});
// معماری رو jwt 
// فرقی نمیکنه از jwt یا sanctum