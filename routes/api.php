<?php

use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\WorkspaceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// چون api نیاز به صفحات html , فرانت نداره 
// متد get رو نمینویسیم 
// زمانی که کاربر دکمه ورود و ثبت نام میزنه داده های جیسون مثل ایمیل و پسورد به api ارسال میشه
Route::post('/login', [ApiAuthController::class, 'login']);
Route::post('/register', [ApiAuthController::class, 'register']);

Route::prefix('v1')->middleware('auth:sanctum')->as('api.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::apiResource('workspaces', WorkspaceController::class);
    Route::post('workspaces/{workspace}/members', [WorkspaceController::class, 'addmember']);
    Route::apiResource('projects', ProjectController::class);
    Route::apiResource('tasks', TaskController::class);
    Route::post('/logout', [AuthController::class, 'logout']);
});

// معماری رو jwt 
// فرقی نمیکنه از jwt یا sanctum