<?php

use App\Http\Controllers\Activity_logController;
use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\ChecklistController;
use App\Http\Controllers\ChecklistItemController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KanbanController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectExportController;
use App\Http\Controllers\ProjectStatusController;
use App\Http\Controllers\SubtaskController;
use App\Http\Controllers\Task_DependenciesController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\Time_logController;
use App\Http\Controllers\Workspace_InvitationController;
use App\Http\Controllers\WorkspaceController;
use App\Models\Checklist;
use App\Models\task_dependencies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [ApiAuthController::class, 'login']);
Route::post('/register', [ApiAuthController::class, 'register']);

Route::prefix('v1')->middleware(['auth:sanctum'])->as('api.')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::get('/notifications/unread', [NotificationController::class, 'unread']);
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);
    Route::get('/dashboard/personal', [DashboardController::class, 'personaldashboard']);
    Route::get('/projects/{project}/dashboard', [DashboardController::class, 'projectdashboard']);
    Route::get('/workspaces', [WorkspaceController::class, 'index']);
    Route::post('/workspaces', [WorkspaceController::class, 'store']);
    Route::post('invitations/{token}/accept', [Workspace_InvitationController::class, 'accept']);
});

// scopebindings برای اینکه مطمین شه مثلا هر تسک متعلق به پروژه خودشه
Route::prefix('v1')->middleware('auth:sanctum', 'workspace.permission')->as('api.')->scopeBindings()->group(function () {
    Route::get('/user', [ApiAuthController::class, 'user']);
    Route::apiResource('workspaces', WorkspaceController::class)->except('store', 'index');
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
    Route::apiResource('workspaces.projects.statuses', ProjectStatusController::class);

    Route::apiResource('workspaces.projects.tasks', TaskController::class);
    Route::patch('/workspaces/{workspace}/projects/{project}/tasks/{task}/status', [TaskController::class, 'updatestatus']);
    Route::post('/workspaces/{workspace}/projects/{project}/tasks/{task}/dependencies', [Task_DependenciesController::class, 'store']);
    Route::delete('/workspaces/{workspace}/projects/{project}/tasks/{task}/dependencies/{dependency}', [Task_DependenciesController::class, 'destroy']);
    Route::get('/workspaces/{workspace}/projects/{project}/tasks/{task}/activities', [Activity_logController::class, 'index']);

    Route::apiResource('workspaces.projects.tasks.checklists', ChecklistController::class);
    // Route::post('/workspaces/{workspace}/projects/{project}/tasks/{task}/checklists/{checklist}/checklist_item', [ChecklistController::class, 'updatestatus']);
    Route::post('/workspaces/{workspace}/projects/{project}/tasks/{task}/checklists/{checklist}/items', [ChecklistItemController::class, 'store']);
    Route::delete('/workspaces/{workspace}/projects/{project}/tasks/{task}/checklists/{checklist}/items/{item}', [ChecklistItemController::class, 'destroy']);
    Route::patch('/workspaces/{workspace}/projects/{project}/tasks/{task}/checklists/{checklist}/items/{item}/tik', [ChecklistItemController::class, 'tik']);

    Route::apiResource('workspaces.projects.tasks.subtasks', SubtaskController::class);

    Route::apiResource('workspaces.projects.tasks.comments', CommentController::class);

    Route::apiResource('workspaces.projects.tasks.attachments', AttachmentController::class);
    Route::get('/workspaces/{workspace}/projects/{project}/tasks/{task}/attachments/{attachment}/download', [AttachmentController::class, 'download']);

    Route::apiResource('workspaces.projects.tasks.time_logs', Time_logController::class);

    // برای نمایش بورد کانبان و تسک‌های مرتب‌شده
    Route::get('/workspaces/{workspace}/projects/{project}/kanban', [KanbanController::class, 'index'])->name('projects.kanban');
    // برای آپدیت موقعیت و وضعیت تسک (جابه‌جایی در کانبان)
    Route::patch('/workspaces/{workspace}/projects/{project}/tasks/{task}/updateposition', [KanbanController::class, 'updateposition'])->name('tasks.updateposition');

    Route::get('/workspaces/{workspace}/projects/{project}/export_csv', [ProjectExportController::class, 'exportcsv']);

    Route::post('/logout', [ApiAuthController::class, 'logout']);
});
