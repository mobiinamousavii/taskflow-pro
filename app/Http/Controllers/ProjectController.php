<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\project_status;
use App\Models\role;
use App\Models\Workspace;
use App\Models\workspace as ModelsWorkspace;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProjectController extends Controller
{
    //     public function __construct()
    // {
    //     $this->authorizeResource(Project::class);
    // }
    /**
     * 
     */
    public function show(Workspace $workspace, Project $project): JsonResponse
    {
        $this->authorize('view', [$project]);
        return response()->json([
            'status' => 'success',
            'data'   => $project->load(['workspace', 'users'])
        ]);
    }

    /**
     * 
     */
    // اینجا استاتوس بصورت اینام تو مایگریشنه و ربطی به  مایگریشن پروجکت استاتوس نداره
    // پروجکت استاتوس برای تسک فلو و مربوط به تسک هاست
    public function store(Request $request, Workspace $workspace): JsonResponse
    {
        $this->authorize('create', [$workspace]);
        // dd('bb');
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                // بررسی اینکه اسم پروژه در این ورک‌اسپیس تکراری نباشه
                Rule::unique('projects')->where(function ($query) use ($workspace) {
                    return $query->where('workspace_id', $workspace->id);
                }),
            ],
            'description' => 'nullable|string',
            'status' => 'nullable|in:planning,active,on_hold,completed,cancelled',
            'start_date'  => 'required|date',
            'deadline'    => 'required|date',
        ]);

        // dd('bb');
        $project = $workspace->projects()->create([
            'name'        => $validated['name'],
            'description' => $validated['description'] ?? null,
            'start_date'  => $validated['start_date'],
            'deadline'    => $validated['deadline'],
            'status' => $validated['status'] ?? 'planning',
            'created_by'  => Auth::id(),
        ]);

        $workspaceRoleId = $workspace->users()
            ->where('users.id', Auth::id())
            ->first()
            ?->pivot->role_id;

        $project->members()->attach(Auth::id(), [
            'role_id' => $workspaceRoleId,
        ]);

        $defaultstatuses = [
            ['name' => 'backlog', 'position' => 1, 'is_completed' => false],
            ['name' => 'todo', 'position' => 2, 'is_completed' => false],
            ['name' => 'inprogress', 'position' => 3, 'is_completed' => false],
            ['name' => 'review', 'position' => 4, 'is_completed' => false],
            ['name' => 'done', 'position' => 5, 'is_completed' => true],
        ];

        foreach ($defaultstatuses as $statusdata) {
            $project->status()->create($statusdata);
        }

        return response()->json([
            'message' => 'پروژه با موفقیت ایجاد شد.',
            'data'    => $project->load('status')
        ]);
    }
    /**
     */
    public function update(Request $request, Workspace $workspace, Project $project): JsonResponse
    {
        $this->authorize('update', [$project]);
        $validated = $request->validate([
            'name'        => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'sometimes|in:planning,active,on_hold,completed,cancelled',
            'start_date'  => 'sometimes|date',
            'deadline'    => 'sometimes|date',
        ]);

        $project->update($validated);

        return response()->json([
            'message' => 'پروژه با موفقیت به‌روزرسانی شد.',
            'data'    => $project
        ]);
    }


    /**
     *
     */
    public function destroy(Workspace $workspace, Project $project): JsonResponse
    {
        $this->authorize('delete', [$project]);
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

        $this->authorize('getmembers', $workspace);
        // کاربران عضو این پروژه با نقش
        $members = $project->users;
        return response()->json([
            'data' => $members
        ]);
    }

    public function addmembers(Request $request, Workspace $workspace, Project $project)
    {
        $this->authorize('addmembers', $workspace);
        $validated = $request->validate([
            'user_id' => [
                'required',
                Rule::exists('workspace_users', 'user_id')->where('workspace_id', $workspace->id),
            ],
            'role_id' => [
                'required',
                Rule::exists('roles', 'id')->where('workspace_id', $workspace->id),
            ],
        ]);


        $userid = $request->input('user_id');

        // dd([
        //     'projectid' => $project->id,
        //     'user id to attach ' => $userid,
        //     'itsarray ? ' => is_array($userid),
        // ]);

        // از syncWithoutDetaching استفاده می‌کنیم که اگر کاربر قبلاً اضافه شده بود، ارور ندهد و آپدیتش کند
        $project->users()->syncWithoutDetaching([
            $validated['user_id'] => ['role_id' => $validated['role_id']]
        ]);

        return response()->json([
            'message' => 'کاربر با موفقیت به پروژه اضافه شد',
        ]);
    }

    public function removemembers(Request $request, Workspace $workspace, Project $project)
    {
        $this->authorize('removemembers', $workspace);
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);
        $project->users()->detach($validated['user_id']);
        return response()->json([
            'message' => 'عضو با موفقیت از پروژه حذف شد',
        ]);
    }
}
