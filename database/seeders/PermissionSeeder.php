<?php

namespace Database\Seeders;

use App\Models\permission;
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
            ['name' => 'مشاهده ورک اسپیس', 'slug' => 'view_workspace', 'workspace_id' => $workspaceId],
            ['name' => ' ویرایش ورک اسپیس', 'slug' => 'edit_workspace', 'workspace_id' => $workspaceId],
            ['name' => ' حذف ورک اسپیس', 'slug' => 'delete_workspace', 'workspace_id' => $workspaceId],
            ['name' => ' مدیریت اعضای ورک اسپیس', 'slug' => 'members_workspace', 'workspace_id' => $workspaceId],

            ['name' => 'مشاهده پروژه', 'slug' => 'view_projects', 'workspace_id' => $workspaceId],
            ['name' => 'ایجاد پروژه', 'slug' => 'create_projects', 'workspace_id' => $workspaceId],
            ['name' => 'ویرایش پروژه', 'slug' => 'edit_projects', 'workspace_id' => $workspaceId],
            ['name' => ' حذف پروژه', 'slug' => 'delete_projects', 'workspace_id' => $workspaceId],

            ['name' => 'مشاهده تسک', 'slug' => 'view_task'  ,'workspace_id' => $workspaceId],
            ['name' => 'ایجاد تسک', 'slug' => 'create_task', 'workspace_id' => $workspaceId],
            ['name' => 'ویرایش تسک', 'slug' => 'edit_task ', 'workspace_id' => $workspaceId],
            ['name' => 'حذف تسک', 'slug' => 'delete_task ', 'workspace_id' => $workspaceId],

            ['name' => 'مشاهده ساب تسک', 'slug' => 'view_subtask ', 'workspace_id' => $workspaceId],
            ['name' => 'ایجاد ساب تسک', 'slug' => 'create_subtask ', 'workspace_id' => $workspaceId],
            ['name' => 'ویرایش ساب تسک', 'slug' => ' edit_subtask', 'workspace_id' => $workspaceId],
            ['name' => 'حذف ساب تسک', 'slug' => 'delete_subtask ', 'workspace_id' => $workspaceId],
        ];


      foreach ($permissions as $permission) {
            // از updateOrCreate استفاده می‌کنیم تا اگر قبلاً بود دوباره تکرار نشود
            permission::updateOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }
    }
}
