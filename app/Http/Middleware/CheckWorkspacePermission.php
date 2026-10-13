<?php


namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckWorkspacePermission
{
    public function handle(Request $request, Closure $next): Response
    {
        // ۱. گرفتن User از سیستم Authentication
        $user = $request->user();

        // ۲. گرفتن ID ورک‌اسپیس از پارامترهای روت (مثلا در آدرس workspaces/{workspace}/projects)
        $workspaceId = $request->route('workspace');

        // ۳. گرفتن نام روت فعلی یا اسلاگ پرمیشن مورد نیاز بر اساس آن
        $permissionSlug = $request->route()->getName(); // یا نگاشت دستی روت به پرمیشن

        if (!$user || (!$user->hasPermissionTo($permissionSlug, $workspaceId) && $user->role !== 'owner')) {
            abort( 'شما اجازه دسترسی به این بخش را ندارید.');
        }

        return $next($request);
    }
}