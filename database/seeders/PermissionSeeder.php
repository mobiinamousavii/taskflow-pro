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
        $permissions = [
            ['name' => 'مشاهده ورک اسپیس', 'slug' => 'view_workspace'],
            ['name' => ' ویرایش ورک اسپیس', 'slug' => 'edit_workspace'],
            ['name' => ' حذف ورک اسپیس', 'slug' => 'delete_workspace'],
            ['name' => ' مدیریت اعضای ورک اسپیس', 'slug' => 'members_workspace'],
            ['name' => 'ایجاد نقش', 'slug' => 'create_role'],


            ['name' => 'مشاهده پروژه', 'slug' => 'view_projects'],
            ['name' => 'ایجاد پروژه', 'slug' => 'create_projects'],
            ['name' => 'ویرایش پروژه', 'slug' => 'edit_projects'],
            ['name' => ' حذف پروژه', 'slug' => 'delete_projects'],


            ['name' => 'مشاهده وضعیت‌های پروژه', 'slug' => 'view_status'],
            ['name' => 'ایجاد وضعیت پروژه', 'slug' => 'create_status'],
            ['name' => 'ویرایش وضعیت پروژه', 'slug' => 'edit_status'],
            ['name' => 'حذف وضعیت پروژه', 'slug' => 'delete_status'],


            ['name' => 'مشاهده تسک', 'slug' => 'view_task'],
            ['name' => 'ایجاد تسک', 'slug' => 'create_task'],
            ['name' => ' ویرایش تسک و ویرایش پوزیشن تسک ها در کانبان بورد', 'slug' => 'edit_task'],
            ['name' => 'حذف تسک', 'slug' => 'delete_task '],
            ['name' => ' خروجی گرفتن از تسک', 'slug' => 'export_task'],

            ['name' => 'مشاهده ساب تسک', 'slug' => 'view_subtask'],
            ['name' => 'ایجاد ساب تسک', 'slug' => 'create_subtask'],
            ['name' => 'ویرایش ساب تسک', 'slug' => ' edit_subtask'],
            ['name' => 'حذف ساب تسک', 'slug' => 'delete_subtask'],

            ['name' => 'مشاهده چک لیست', 'slug' => 'view_checklist'],
            ['name' => 'ایجاد چک لیست', 'slug' => 'create_checklist'],
            ['name' => 'ویرایش چک لیست', 'slug' => 'edit_checklist'],
            ['name' => 'حذف چک لیست', 'slug' => 'delete_checklist'],

            ['name' => 'ایجاد کامنت', 'slug' => 'create_comment'],
            ['name' => 'ویرایش کامنت', 'slug' => 'edit_comment'],
            ['name' => 'حذف کامنت', 'slug' => 'delete_comment '],

            ['name' => 'ایجاد فایل', 'slug' => 'create_attachment'],
            ['name' => 'ویرایش فایل', 'slug' => 'edit_attachment'],
            ['name' => 'حذف فایل', 'slug' => 'delete_attachment'],
            ['name' => 'دانلود فایل', 'slug' => 'download_attachment'],

            ['name' => ' مشاهده زمان ثبت شده', 'slug' => 'view_time_log'],
            ['name' => 'ثبت زمان', 'slug' => 'create_time_log'],
            ['name' => 'ویرایش زمان', 'slug' => 'edit_time_log'],
            ['name' => 'حذف زمان', 'slug' => 'delete_time_log'],

            ['name' => 'دانلود فایل خروجی csv', 'slug' => 'export_csv'],
        ];


        foreach ($permissions as $permission) {
           
            permission::updateOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }
    }
}
