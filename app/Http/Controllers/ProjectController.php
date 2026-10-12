<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * مشاهده لیست پروژه‌های یک ورک‌اسپیس یا مشاهده یک پروژه خاص
     */
    public function show(Project $project): JsonResponse
    {
        // فراخوانی متد view در ProjectPolicy
        $this->authorize('view', $project);

        return response()->json([
            'status' => 'success',
            'data'   => $project->load(['workspace', 'users'])
        ]);
    }

    /**
     * ایجاد پروژه جدید در یک Workspace مشخص
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'workspace_id' => 'required|exists:workspaces,id',
            'name'         => 'required|string|max:255',
            'description'  => 'nullable|string',
            'start_date'=>'required|date',
            'deadline'=>'required|date',
        ]);

        $workspace = Workspace::findOrFail($validated['workspace_id']);

        // فراخوانی متد create در ProjectPolicy
        // نکته: چون پروژه هنوز ساخته نشده، کلاس Project و شیء Workspace پاس داده می‌شود
        $this->authorize('create', [Project::class, $workspace]);

        $project = $workspace->projects()->create([
            'name'        => $validated['name'],
            'description' => $validated['description'] ?? null,
            'start_date'=>$validated['start_date'],
            'deadline'=>$validated['deadline'],
            'created_by' => Auth::id(),
        ]);

        return response()->json([
            'message' => 'پروژه با موفقیت ایجاد شد.',
            'data'    => $project
        ], 201);
    }

    /**
     * ویرایش اطلاعات پروژه
     */
    public function update(Request $request, Project $project): JsonResponse
    {
        // فراخوانی متد update در ProjectPolicy
        $this->authorize('update', $project);

        $validated = $request->validate([
            'name'        => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $project->update($validated);

        return response()->json([
            'message' => 'پروژه با موفقیت به‌روزرسانی شد.',
            'data'    => $project
        ]);
    }

    /**
     * حذف پروژه
     */
    public function destroy(Project $project): JsonResponse
    {
        // فراخوانی متد delete در ProjectPolicy
        $this->authorize('delete', $project);

        $project->delete();

        return response()->json([
            'message' => 'پروژه با موفقیت حذف شد.'
        ]);
    }

    /**
     * مدیریت و افزودن/حذف اعضای پروژه
     */
    public function manageMembers(Request $request, Project $project): JsonResponse
    {
        // فراخوانی متد manageMembers در ProjectPolicy
        $this->authorize('manageMembers', $project);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role'    => 'required|string|in:admin,member,viewer',
        ]);

        // سینک یا افزودن کاربر به پروژه در جدول پیوت project_user
        $project->users()->syncWithoutDetaching([
            $validated['user_id'] => ['role' => $validated['role']]
        ]);

        return response()->json([
            'message' => 'اعضای پروژه با موفقیت مدیریت شدند.'
        ]);
    }
}
