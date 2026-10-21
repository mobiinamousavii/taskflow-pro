<?php

use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\Workspace_InvitationController;
use App\Http\Controllers\WorkspaceController;
use App\Models\Checklist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [ApiAuthController::class, 'login']);
Route::post('/register', [ApiAuthController::class, 'register']);

Route::prefix('v1')->middleware(['auth:sanctum'])->as('api.')->group(function () {
    Route::post('/workspaces', [WorkspaceController::class, 'store']);
    Route::post('invitations/{token}/accept', [Workspace_InvitationController::class, 'accept']);
});

Route::prefix('v1')->middleware('auth:sanctum', 'workspace.permission')->as('api.')->group(function () {
    Route::get('/user', [ApiAuthController::class, 'user']);
    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::apiResource('workspaces', WorkspaceController::class)->except('store');
    Route::get('workspaces/{workspace}/members', [WorkspaceController::class, 'members']);
    Route::post('workspaces/{workspace}/members', [WorkspaceController::class, 'addmember']);
    // مدیریت نقش‌های داینامیک این ورک‌اسپیس
    Route::get('/workspaces/{workspace}/roles', [WorkspaceController::class, 'roles']);
    Route::post('/workspaces/{workspace}/roles', [WorkspaceController::class, 'storeRole']);

    // روت اختصاص پرمیشن به رول در ورک‌اسپیس
    Route::post('workspaces/{workspace}/roles/{role}/permissions', [RolePermissionController::class, 'syncPermissions']);

    Route::post('workspaces/{workspace}/invitations', [Workspace_InvitationController::class, 'store']);
    Route::delete('/workspaces/{workspace}/invitations/{invitation}', [Workspace_InvitationController::class, 'destroy']);

    Route::apiResource('workspaces.projects', ProjectController::class);
    Route::get('/workspaces/{workspace}/projects/{project}/members', [ProjectController::class, 'getmembers']);
    Route::post('/workspaces/{workspace}/projects/{project}/members', [ProjectController::class, 'addmembers']);
    Route::delete('/workspaces/{workspace}/projects/{project}/members', [ProjectController::class, 'removemembers']);
    Route::patch('/workspaces/{workspace}/projects/{project}/status', [ProjectController::class, 'updatestatus']);

    Route::apiResource('workspaces.projects.tasks', TaskController::class);
    Route::patch('/workspaces/{workspace}/projects/{project}/tasks/{task}/status', [TaskController::class, 'updatestatus']);

    Route::apiResource('checklists', Checklist::class);


    // Route::apiResource('checklists', subtask::class);

    Route::post('/logout', [ApiAuthController::class, 'logout']);
});
