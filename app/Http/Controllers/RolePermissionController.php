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

        // پاک کردن پرمیشن های قبلی تو ورک اسپیس  براساس رول ایدی
        DB::table('role_permissions')
            ->where('role_id', $roleId)
            ->where('workspace_id', $workspaceId)
            ->delete();

        // اضافه کردن پرمیشن های جدید براساس رول ایدی
        $data = [];
        foreach ($request->permissions as $permissionId) {
            $data[] = [
                'permission_id' => $permissionId,
                'role_id' => $roleId,
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
