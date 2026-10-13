<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\project_status;
use App\Models\Workspace;
use App\Models\workspace as ModelsWorkspace;
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

        return response()->json([
            'status' => 'success',
            'data'   => $project->load(['workspace', 'users'])
        ]);
    }

    /**
     * ایجاد پروژه جدید در یک Workspace مشخص
     */
    public function store(Request $request, Workspace $workspace): JsonResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                // بررسی اینکه نام پروژه در این ورک‌اسپیس تکراری نباشد
                \Illuminate\Validation\Rule::unique('projects')->where(function ($query) use ($workspace) {
                    return $query->where('workspace_id', $workspace->id);
                }),
            ],
            'description' => 'nullable|string',
            'start_date'  => 'required|date',
            'deadline'    => 'required|date',
        ]);

        $defualtstatus = project_status::firstOrCreate([
            'workspace_id' => $workspace->id,
            'name' => 'active',
        ]);

        $project = $workspace->projects()->create([
            'name'        => $validated['name'],
            'description' => $validated['description'] ?? null,
            'start_date'  => $validated['start_date'],
            'deadline'    => $validated['deadline'],
            'project_status_id' => $defualtstatus->id,
            'created_by'  => Auth::id(),
        ]);

        return response()->json([
            'message' => 'پروژه با موفقیت ایجاد شد.',
            'data'    => $project->load('status')
        ]);
    }
    /**
     * ویرایش اطلاعات پروژه
     */
    public function update(Request $request, Project $project): JsonResponse
    {

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



    public function updatestatus(Request $request, Workspace $workspace, Project $project): JsonResponse
    {

        $validated = $request->validate([
            'status'        => 'required|string|max:255|in:active,cancelled,completed,on hold ,planning ',
        ]);

        $newstatus = project_status::firstOrCreate([
            'workspace_id' => $workspace->id,
            'name' => $validated['status'],
        ]);

        $project->update([
            'project_status_id' => $newstatus->id,
        ]);

        return response()->json([
            'message' => 'پروژه با موفقیت به‌روزرسانی شد.',
            'data'    => $project->load('status')
        ]);
    }

    /**
     * حذف پروژه
     */
    public function destroy(Workspace $workspace, Project $project): JsonResponse
    {

        $project->delete();

        return response()->json([
            'message' => 'پروژه با موفقیت حذف شد.'
        ]);
    }

    /**
     * مدیریت و افزودن/حذف اعضای پروژه
     */
    public function getmembers(Workspace $workspace, Project $project)
    {
        // dd($project);

        // کاربران عضو این پروژه با نقش
        $members = $project->users;
        return response()->json([
            'data' => $members
        ]);
    }

    public function addmembers(Request $request, Workspace $workspace, Project $project)
    {
        // ۱. ولیدیشن اطلاعات ارسالی
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|string|in:manager,developer,viewer', // نقش‌های مجاز
        ]);

        // ۲. اتصال کاربر به پروژه همراه با ذخیره نقش در جدول pivot
        // از syncWithoutDetaching استفاده می‌کنیم که اگر کاربر قبلاً اضافه شده بود، ارور ندهد و آپدیتش کند
        $project->users()->syncWithoutDetaching([
            $validated['user_id'] => ['role' => $validated['role']]
        ]);

        return response()->json([
            'message' => 'کاربر با موفقیت به پروژه اضافه شد',
        ]);
    }

    public function removemember(Request $request, Project $project)
    {

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);
        $project->users()->detach($validated['user_id']);
        return response()->json([
            'message' => 'عضو با موفقیت از پروژه حذف شد',
        ]);
    }
}


// جلوگیری از ایجاد پروژه تکراری
// واسه هر ورک اسپیسی میخام پروژه بسازم دیفالت رو 10 میسازه