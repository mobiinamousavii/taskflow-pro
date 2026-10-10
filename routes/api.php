<?php

use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\Workspace_InvitationController;
use App\Http\Controllers\WorkspaceController;
use App\Models\workspace_invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// چون api نیاز به صفحات html , فرانت نداره 
// متد get رو نمینویسیم 
// زمانی که کاربر دکمه ورود و ثبت نام میزنه داده های جیسون مثل ایمیل و پسورد به api ارسال میشه
Route::post('/login', [ApiAuthController::class, 'login']);
Route::post('/register', [ApiAuthController::class, 'register']);

Route::prefix('v1')->middleware('auth:sanctum')->as('api.')->group(function () {
    Route::get('/user', [ApiAuthController::class, 'user']);
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::apiResource('workspaces', WorkspaceController::class);
    Route::get('workspaces/{workspace}/members', [WorkspaceController::class, 'members']);
    Route::post('workspaces/{workspace}/members', [WorkspaceController::class, 'addmember']);
    // دعوت نامه برای کدوم ورک اسپیسه  {workspace} اینو مینویسیم
    Route::post('workspaces/{workspace}/invitations', [Workspace_InvitationController::class, 'store']);
    // چون کاربر با توکنش دعوت نامه میپزیره 
    Route::post('invitations/{token}/accept', [Workspace_InvitationController::class, 'accept']);
    Route::delete('/workspaces/{workspace}/invitations/{invitation}', [Workspace_InvitationController::class, 'destroy']);
    Route::apiResource('projects', ProjectController::class);
    Route::apiResource('tasks', TaskController::class);
    Route::post('/logout', [ApiAuthController::class, 'logout']);
});

// معماری رو jwt 
// فرقی نمیکنه از jwt یا sanctum