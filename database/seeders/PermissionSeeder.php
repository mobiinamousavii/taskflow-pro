<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $workspaceId = 1;
        $permissions = [
            ['name' => 'ویرایش پروژه', 'slug' => 'edit_projects', 'workspace_id' => $workspaceId],
            ['name' => 'ایجاد پروژه', 'slug' => 'create_projects', 'workspace_id' => $workspaceId],
            ['name' => ' حذف پروژه', 'slug' => 'delete_projects', 'workspace_id' => $workspaceId],
            ['name' => 'حذف تسک', 'slug' => 'delete_tasks', 'workspace_id' => $workspaceId],
        ];

        foreach ($permissions as $permission) {
            DB::table('permissions')->insertOrIgnore($permission);
        }
    }
}
