<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Workspace;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    /**
     * تعریف نقش جدید در ورک‌اسپیس توسط Owner
     */
    public function store(Request $request, Workspace $workspace)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100', // e.g. "developer" یا "ادیتور"
            'label' => 'nullable|string|max:255',
            'permissions' => 'required|array',  // آرایه‌ای از ID پرمیشن‌ها: [1, 2, 6, 7]
            'permissions.*' => 'exists:permissions,id',
        ]);

        return DB::transaction(function () use ($validated, $workspace) {
            // ۱. ثبت نقش جدید برای این ورک‌اسپیس
            $role = Role::create([
                'workspace_id' => $workspace->id,
                'name' => $validated['name'],
                'label' => $validated['label'] ?? $validated['name'],
            ]);

            // ۲. ثبت پرمیشن‌های انتخابی در جدول role_permissions
            $rolePermissions = array_map(function ($permissionId) use ($workspace, $role) {
                return [
                    'workspace_id' => $workspace->id,
                    'role_id' => $role->id,
                    'permission_id' => $permissionId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }, $validated['permissions']);

            DB::table('role_permissions')->insert($rolePermissions);

            return response()->json([
                'status' => 'success',
                'message' => 'نقش با موفقیت ساخته شد و پرمیشن‌ها تخصیص یافتند.',
                'role' => $role->load('permissions')
            ], 201);
        });
    }

    /**
     * دریافت تمام نقش‌های تعریف‌شده در این ورک‌اسپیس
     */
    public function index(Workspace $workspace)
    {
        $roles = Role::where('workspace_id', $workspace->id)->get();

        return response()->json([
            'status' => 'success',
            'roles' => $roles
        ]);
    }
}
