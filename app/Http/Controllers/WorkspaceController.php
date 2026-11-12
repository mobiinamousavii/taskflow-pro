<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Workspace;
use App\Models\Permission;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class WorkspaceController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Workspace::class, 'workspace');
    }
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $workspaces = $request->user()->workspaces()->withCount('projects')->get();

        return response()->json([
            'status' => 'success',
            'data' => $workspaces
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($request) {
            $workspace = Workspace::create([
                'owner_id' => Auth::id(),
                'name' => $request->name,
                'description' => $request->description,
            ]);

            $ownerRole = Role::firstOrCreate(
                [
                    'workspace_id' => $workspace->id,
                    'name' => 'owner',
                ],
                [
                    'label' => 'مالک ورک‌اسپیس',
                ]
            );

            $workspace->users()->attach(Auth::id(), [
                'role_id' => $ownerRole->id
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'ورک اسپیس با موفقیت ایجاد شد',
                'workspace' => $workspace->load('roles'),
            ]);
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(Workspace $workspace): JsonResponse
    {
        $this->authorize('view', $workspace);
        return response()->json([
            'status' => 'success',
            'data' => $workspace
        ]);
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Workspace $workspace): JsonResponse
    {
        $this->authorize('update', $workspace);
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $workspace->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'ورک‌اسپیس با موفقیت به‌روزرسانی شد.',
            'data' => $workspace
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Workspace $workspace): JsonResponse
    {
        $this->authorize('delete', $workspace);
        $workspace->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'ورک‌اسپیس با موفقیت حذف شد.'
        ]);
    }

    /**
     * لیست اعضای ورک‌اسپیس
     */
    public function members(Workspace $workspace): JsonResponse
    {
        $this->authorize('view', $workspace);
        return response()->json([
            'status' => 'success',
            'data' => $workspace->users
        ]);
    }

    /**
     *  اضافه کردن عضو به ورک‌اسپیس
     */
    public function addmember(Request $request, Workspace $workspace): JsonResponse
    {
        $this->authorize('addMember', $workspace);
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'role'  => 'required|string'
        ]);

        $userToAdd = User::where('email', $request->email)->first();

        if ($workspace->users()->where('user_id', $userToAdd->id)->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'این کاربر قبلاً عضو ورک‌اسپیس شده است.'
            ]);
        }

        $role = Role::where('workspace_id', $workspace->id)
            ->where('name', $request->role)
            ->first();

        if (!$role) {
            return response()->json([
                'status' => 'error',
                'message' => 'رول مشخص شده برای این ورک‌اسپیس معتبر نیست.'
            ]);
        }

        $workspace->users()->attach($userToAdd->id, [
            'role_id' => $role->id
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'کاربر با موفقیت اضافه شد.'
        ]);
    }

    /**
     * گرفتن لیست نقش‌های تعریف‌شده در این ورک‌اسپیس
     */
    public function roles(Workspace $workspace): JsonResponse
    {
        $this->authorize('manageRoles', $workspace);
        $roles = Role::where('workspace_id', $workspace->id)->with('permissions')->get();

        return response()->json([
            'status' => 'success',
            'data' => $roles
        ]);
    }

    /**
     * ایجاد رول توسط اونر هر ورک اسپیس برای همون ورک اسپیس
     */
    public function storeRole(Request $request, Workspace $workspace): JsonResponse
    {
        $this->authorize('manageRoles', $workspace);
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('roles')->where(function ($query) use ($workspace) {
                    return $query->where('workspace_id', $workspace->id);
                }),
            ],
            'label' => 'nullable|string|max:255',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        return DB::transaction(function () use ($request, $workspace) {
            $role = Role::create([
                'workspace_id' => $workspace->id,
                'name' => $request->name,
                'label' => $request->label ?? $request->name,
            ]);

            $rolePermissions = [];
            foreach ($request->permissions as $permissionId) {
                $rolePermissions[] = [
                    'workspace_id' => $workspace->id,
                    'role_id' => $role->id,
                    'permission_id' => $permissionId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            if (!empty($rolePermissions)) {
                DB::table('role_permissions')->insert($rolePermissions);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'نقش جدید با موفقیت در این ورک‌اسپیس ساخته شد.',
                'data' => $role->load('permissions')
            ]);
        });
    }
}
