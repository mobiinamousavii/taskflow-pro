<?php

namespace App\Policies;

use Illuminate\Http\Request;
use App\Models\User;

class DynamicPermissionPolicy
{
    // protected $request;

    // public function __construct(Request $request)
    // {
    //     $this->request = $request;
    // }

    // /**
    //  * متد اصلی برای بررسی دسترسی کاربر
    //  */
    // public function CheckPermission(User $user)
    // {
    //     // ۱. بررسی اینکه آیا کاربر در این ورک‌اسپیسِ خاص نقش Owner است یا خیر
    //     $workspaceId = $this->request->route('workspace') ?? $this->request->input('workspace_id');

    //     if ($workspaceId) {
    //         $isOwner = $user->workspaces()
    //             ->where('workspaces.id', $workspaceId)
    //             ->wherePivot('role', 'owner') // یا نام ستونی که نقش را در جدول واسط نگه می‌دارد
    //             ->exists();

    //         if ($isOwner) {
    //             return true; // اگر اونر این ورک‌اسپیس بود، دسترسی کامل دارد
    //         }
    //     }

    //     // ۲. گرفتن نام روت فعلی برای بقیه کاربران یا سایر بخش‌ها
    //     $routeName = $this->request->route() ? $this->request->route()->getName() : null;

    //     if ($routeName) {
    //         // ۳. تبدیل نام روت به پرمیشن دیتابیس
    //         $permission = $this->convertToPermission($routeName);

    //         // ۴. بررسی اینکه آیا کاربر این پرمیشن را دارد یا خیر
    //         if ($permission) {
    //             return $user->hasPermission($permission);
    //         }
    //     }

    //     return false;
    // }

    // /**
    //  * تبدیل نام روت (مثل projects.destroy) به نام پرمیشن (مثل delete_project)
    //  */
    // protected function convertToPermission($routeName)
    // {
    //     // حذف پیشوند احتمالی ادمین یا بخش‌ها
    //     if (str_starts_with($routeName, 'admin.')) {
    //         $routeName = substr($routeName, 6);
    //     }

    //     // بررسی اینکه آیا نام روت شامل نقطه (Resourceful Routes) است یا خیر
    //     if (str_contains($routeName, '.')) {
    //         [$resource, $action] = explode('.', $routeName);

    //         // مپ کردن متدهای لاراول به عملیات‌های استاندارد پرمیشن
    //         $actionMap = [
    //             'index'   => 'view',
    //             'show'    => 'view',
    //             'create'  => 'create',
    //             'store'   => 'create',
    //             'edit'    => 'edit',
    //             'update'  => 'edit',
    //             'destroy' => 'delete',
    //         ];

    //         if (isset($actionMap[$action])) {
    //             // اگر نام ریسورس جمع بود، مفردش می‌کنیم
    //             if (str_ends_with($resource, 's')) {
    //                 $resource = rtrim($resource, 's');
    //             }
    //             return $actionMap[$action] . '_' . $resource; // مثل: delete_project
    //         }
    //     }

    //     return $routeName;
    // }
}
