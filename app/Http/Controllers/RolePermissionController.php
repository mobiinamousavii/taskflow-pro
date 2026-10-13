<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RolePermissionController extends Controller
{
    public function syncPermissions(Request $request, $workspace, $role)
    {
        //  dd($workspace, $role, $request->all());
        $request->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        // ۱. ابتدا پرمیشن‌های قبلی این رول را در این ورک‌اسپیس (یا به طور کلی) پاک می‌کنیم
        DB::table('role_permissions')
            ->where('role', $role)
            // اگر ستون workspace_id هم در جدول دارید، این شرط را فعال کنید:
            // ->where('workspace_id', $workspace)
            ->delete();

        // ۲. پرمیشن‌های جدید را برای این رول درج می‌کنیم
        $data = [];
        foreach ($request->permissions as $permissionId) {
            $data[] = [
                'permission_id' => $permissionId,
                 'role' => $role,
                'workspace_id' => $workspace, 
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('role_permissions')->insert($data);

        return response()->json([
            'message' => 'پرمیشن‌های نقش ' . $role . ' با موفقیت ثبت شد.',
        ]);
    }
}
