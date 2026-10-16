<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Workspace;
use Illuminate\Support\Facades\Auth;

class CheckWorkspacePermission
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user) {
            abort(401, 'ابتدا باید وارد حساب کاربری خود شوید.');
        }

        // ایدی یا مدل ورک اسپیس رو از url میخونه
        // اگه ایدی عدد بود میره تو دیتابیس پیداش میکنه رکوردشو
        $workspace = $request->route('workspace');

        if (is_numeric($workspace)) {
            $workspace = Workspace::find($workspace);
        }

        if (!$workspace) {
            abort(404, 'ورک‌اسپیس مورد نظر یافت نشد.');
        }

        //  اسم مسیر فعلی رو میگیره و به فرمت پرمیشن تبدیلش میکنه
        $routeName = $request->route() ? $request->route()->getName() : null;
        $permissionName = $this->convertToPermission($routeName);

        // dd($permissionName,$workspace->id);

        //  بررسی پرمیشن در سطح آن ورک‌اسپیس
        // کاربر وارد شده اجازه انجام کار موردنظرو در ورک اسپیس داره یا نه
        // متد هز ورک اسپیس پرمیشن رو در مدل یوزر صدا میزنه ا
        if ($permissionName && !$user->hasWorkspacePermission($workspace, $permissionName)) {
            abort(403, 'شما سطح دسترسی لازم برای این عملیات را در این ورک‌اسپیس ندارید.');
        }

        return $next($request);
    }

    /**
     * تبدیل نام روت (مثل projects.store یا tasks.update) به پرمیشن دیتابیس
     */
    // متدهای لاراول مث استور و ایندکس رو به کلمات کلیدی پرمیشن مثل کریت و ویو
    // مپ میکنه تا با جدول پرمیشن ها هماهنگ بشه
    private function convertToPermission($routeName)
    {
        if (!$routeName) {
            return null;
        }

        // اگر پیشوندی مثل workspace. داشت حذف می‌کنیم
        if (str_starts_with($routeName, 'workspace.')) {
            $routeName = substr($routeName, 10);
        }

        $segments = explode('.', $routeName);
        $action = end($segments); // آخرین بخش نام روت (مثل store) را می‌گیرد
        $resource = $segments[count($segments) - 2] ?? ''; // بخش قبل از آن (مثل projects) را می‌گیرد

        $actionMap = [
            'index' => 'view',
            'show' => 'view',
            'create' => 'create',
            'store' => 'create', // متد store معادل پرمیشنِ create است
            'edit' => 'edit',
            'update' => 'edit',
            'destroy' => 'delete',
        ];

        if (isset($actionMap[$action]) && !empty($resource)) {
            $mappedAction = $actionMap[$action];
            
            // ترکیب برای ساخت نام پرمیشن (مثلاً create_projects)
            return $mappedAction . '_' . $resource; 
        }

        return $routeName;
    }
}