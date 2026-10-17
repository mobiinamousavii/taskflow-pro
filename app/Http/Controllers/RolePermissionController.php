<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RolePermissionController extends Controller
{
    public function syncPermissions(Request $request, $workspaceId, $roleId)
    {
        $request->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        // ۱. پاک کردن پرمیشن‌های قبلی این رول در این ورک‌اسپیس بر اساس role_id
        DB::table('role_permissions')
            ->where('role_id', $roleId)
            ->where('workspace_id', $workspaceId)
            ->delete();

        // ۲. درج پرمیشن‌های جدید با ارتباط به role_id
        $data = [];
        foreach ($request->permissions as $permissionId) {
            $data[] = [
                'permission_id' => $permissionId,
                'role_id' => $roleId, // استفاده از آیدی رول به جای نام متنی
                'workspace_id' => $workspaceId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('role_permissions')->insert($data);

        return response()->json([
            'status' => 'success',
            'message' => 'پرمیشن‌های نقش با موفقیت ثبت شد.',
        ]);
    }
}